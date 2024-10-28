<?php
/*
 * Plugin Name: URL Short
 */
// Plugin backend
global $wpdb;
$table_name = $wpdb->prefix . 'short_url';

// Register the AJAX handler
add_action('wp_ajax_url_short', 'handle_ajax_url_short');
add_action('wp_ajax_nopriv_url_short', 'handle_ajax_url_short');

function handle_ajax_url_short() {

    if (isset($_POST['url'])) {
        $long_url = sanitize_text_field($_POST['url']);
        $short_url = generate_short_url($long_url);

        wp_send_json(['url' => $short_url]);
    }
    wp_die(); // This is required to terminate immediately and return a proper response
}

function enqueue_shorturl_scripts() {
    wp_enqueue_script('shorturl-ajax', plugins_url('ajax-url.js', __FILE__), array('jquery'), null, true);
    wp_localize_script('shorturl-ajax', 'ajax_object', array('ajaxurl' => admin_url('admin-ajax.php')));
}
add_action('wp_enqueue_scripts', 'enqueue_shorturl_scripts');

function generate_short_url($url) {
    global $wpdb, $table_name;

    // Generate a unique token
    $token = substr(md5(uniqid(rand(), true)), 0, 6);

    // Insert the URL and token into the database
    $wpdb->insert(
        $table_name,
        array(
            'original_url' => $url,
            'token' => $token,
        )
    );

    return home_url('/') . '?token=' . $token;
}

function handle_shorturl_form() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['long_url'])) {
        $long_url = sanitize_text_field($_POST['long_url']);

        $short_url = generate_short_url($long_url);

        echo '<div id="shorturl-result">Short URL: <a href="' . $short_url . '">' . $short_url . '</a></div>';
    }
}
add_action('wp_footer', 'handle_shorturl_form');

function shorturl_shortcode($atts) {
    $atts = shortcode_atts(array(
        'url' => '',
    ), $atts, 'shorturl');

    if (empty($atts['url'])) {
        return 'No URL provided.';
    }

    return generate_short_url($atts['url']);
}
add_shortcode('shorturl', 'shorturl_shortcode');

function shorturl_form() {
    ob_start();
    ?>
    <form id="shorturl-form" method="post" action="">
        <input id="url" type="url" name="long_url" placeholder="Enter long URL" required>
        <button type="submit">Shorten</button>
    </form>
    <div id="shorturl-result"></div>
    <?php
    return ob_get_clean();
}
add_shortcode('shorturl_form', 'shorturl_form');

function handle_token_redirect() {
    if (isset($_GET['token'])) {
        global $wpdb, $table_name;
        $token = sanitize_text_field($_GET['token']);

        // Retrieve the original URL from the database
        $original_url = $wpdb->get_var($wpdb->prepare(
            "SELECT original_url FROM $table_name WHERE token = %s",
            $token
        ));

        if ($original_url) {
            wp_redirect($original_url);
            exit;
        }
    }
}
add_action('template_redirect', 'handle_token_redirect');


// Plugin frontend
function shorturl_admin_menu() {
    add_menu_page(
        'URL Shortener', // Page title
        'URL Shortener', // Menu title
        'manage_options', // Capability
        'shorturl-admin', // Menu slug
        'shorturl_admin_page' // Callback function
    );
}
add_action('admin_menu', 'shorturl_admin_menu');

// Display the admin page content
function shorturl_admin_page() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'short_url';

    // Handle delete action
    if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
        $id = intval($_GET['id']);
        $wpdb->delete($table_name, array('id' => $id));
        echo '<div class="updated"><p>URL deleted.</p></div>';
    }

    // Fetch all URLs
    $results = $wpdb->get_results("SELECT * FROM $table_name");

    echo '<div class="wrap">';
    echo '<h1>URL Shortener</h1>';
    echo '<table class="wp-list-table widefat fixed striped">';
    echo '<thead><tr><th>ID</th><th>Original URL</th><th>Token</th><th>Actions</th></tr></thead>';
    echo '<tbody>';
    foreach ($results as $row) {
        echo '<tr>';
        echo '<td>' . esc_html($row->id) . '</td>';
        echo '<td>' . esc_html($row->original_url) . '</td>';
        echo '<td>' . esc_html($row->token) . '</td>';
        echo '<td>';
        echo '<a href="' . admin_url('admin.php?page=shorturl-admin&action=edit&id=' . $row->id) . '">Edit</a> | ';
        echo '<a href="' . admin_url('admin.php?page=shorturl-admin&action=delete&id=' . $row->id) . '" onclick="return confirm(\'Are you sure?\')">Delete</a>';
        echo '</td>';
        echo '</tr>';
    }
    echo '</tbody>';
    echo '</table>';
    echo '</div>';
}

// plugin frontend
// Handle edit action
function shorturl_edit_page() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'short_url';

    if (isset($_POST['submit'])) {
        $id = intval($_POST['id']);
        $original_url = sanitize_text_field($_POST['original_url']);
        $token = sanitize_text_field($_POST['token']);
        $wpdb->update(
            $table_name,
            array(
                'original_url' => $original_url,
                'token' => $token
            ),
            array('id' => $id)
        );
        echo '<div class="updated"><p>URL updated.</p></div>';
    }

    if (isset($_GET['id'])) {
        $id = intval($_GET['id']);
        $row = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $id));
        if ($row) {
            echo '<div class="wrap">';
            echo '<h1>Edit URL</h1>';
            echo '<form method="post" action="">';
            echo '<input type="hidden" name="id" value="' . esc_attr($row->id) . '">';
            echo '<table class="form-table">';
            echo '<tr><th scope="row"><label for="original_url">Original URL</label></th>';
            echo '<td><input name="original_url" type="text" id="original_url" value="' . esc_attr($row->original_url) . '" class="regular-text"></td></tr>';
            echo '<tr><th scope="row"><label for="token">Token</label></th>';
            echo '<td><input name="token" type="text" id="token" value="' . esc_attr($row->token) . '" class="regular-text"></td></tr>';
            echo '</table>';
            echo '<p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes"></p>';
            echo '</form>';
            echo '</div>';
        }
    }
}

// Hook the edit page function
function shorturl_admin_page_hook() {
    if (isset($_GET['page']) && $_GET['page'] == 'shorturl-admin' && isset($_GET['action']) && $_GET['action'] == 'edit') {
        shorturl_edit_page();
        exit;
    }
}
add_action('admin_init', 'shorturl_admin_page_hook');