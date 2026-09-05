<?php
/**
 * Plugin Name: KT-CIMG
 * Plugin URI: https://klikternak.com
 * Description: Plugin powerful dan ringan untuk konversi gambar (WebP/AVIF), pembersihan database, deteksi duplikat, rename massal, dan analisis filesystem. Semua fitur GRATIS!
 * Version: 2.0.0
 * Author: Rizki Adi Saputra
 * Author URI: https://klikternak.com
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: kt-cimg
 * Domain Path: /languages
 */

if (!defined('ABSPATH')) {
    exit;
}

define('KT_CIMG_VERSION', '2.0.0');
define('KT_CIMG_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('KT_CIMG_PLUGIN_URL', plugin_dir_url(__FILE__));
define('KT_CIMG_BACKUP_DIR', wp_upload_dir()['basedir'] . '/kt-cimg-backups/');

class KT_CIMG_Main {
    
    private static $instance = null;
    
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        $this->init_hooks();
        $this->create_backup_dir();
    }
    
    private function init_hooks() {
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_assets'));
        add_action('wp_ajax_kt_cimg_save_settings', array($this, 'ajax_save_settings'));
        add_action('wp_ajax_kt_cimg_convert_bulk', array($this, 'ajax_convert_bulk'));
        add_action('wp_ajax_kt_cimg_scan_duplicates', array($this, 'ajax_scan_duplicates'));
        add_action('wp_ajax_kt_cimg_delete_duplicates', array($this, 'ajax_delete_duplicates'));
        add_action('wp_ajax_kt_cimg_scan_unused', array($this, 'ajax_scan_unused'));
        add_action('wp_ajax_kt_cimg_delete_unused', array($this, 'ajax_delete_unused'));
        add_action('wp_ajax_kt_cimg_preview_rename', array($this, 'ajax_preview_rename'));
        add_action('wp_ajax_kt_cimg_execute_rename', array($this, 'ajax_execute_rename'));
        add_action('wp_ajax_kt_cimg_clean_database', array($this, 'ajax_clean_database'));
        add_action('wp_ajax_kt_cimg_analyze_db', array($this, 'ajax_analyze_db'));
        add_action('wp_ajax_kt_cimg_scan_filesystem', array($this, 'ajax_scan_filesystem'));
        add_action('wp_ajax_kt_cimg_delete_orphaned', array($this, 'ajax_delete_orphaned'));
        add_filter('wp_generate_attachment_metadata', array($this, 'convert_on_upload'), 10, 2);
        add_filter('the_content', array($this, 'serve_modern_images'));
        
        register_activation_hook(__FILE__, array($this, 'activate_plugin'));
    }
    
    public function activate_plugin() {
        $this->create_backup_dir();
    }
    
    private function create_backup_dir() {
        if (!file_exists(KT_CIMG_BACKUP_DIR)) {
            wp_mkdir_p(KT_CIMG_BACKUP_DIR);
            file_put_contents(KT_CIMG_BACKUP_DIR . 'index.php', '<?php // Silence is golden');
        }
    }
    
    public function add_admin_menu() {
        add_menu_page(
            'KT-CIMG',
            'KT-CIMG',
            'manage_options',
            'kt-cimg',
            array($this, 'render_settings_page'),
            'dashicons-images-alt2',
            58
        );
        
        add_submenu_page('kt-cimg', 'Bulk Convert', 'Bulk Convert', 'manage_options', 'kt-cimg-convert', array($this, 'render_convert_page'));
        add_submenu_page('kt-cimg', 'Image Cleaner', 'Image Cleaner', 'manage_options', 'kt-cimg-cleaner', array($this, 'render_cleaner_page'));
        add_submenu_page('kt-cimg', 'Bulk Rename', 'Bulk Rename', 'manage_options', 'kt-cimg-rename', array($this, 'render_rename_page'));
        add_submenu_page('kt-cimg', 'DB Cleaner', 'DB Cleaner', 'manage_options', 'kt-cimg-db', array($this, 'render_db_page'));
        add_submenu_page('kt-cimg', 'Filesystem Analysis', 'Filesystem Analysis', 'manage_options', 'kt-cimg-fs', array($this, 'render_fs_page'));
    }
    
    public function enqueue_assets($hook) {
        if (strpos($hook, 'kt-cimg') === false) {
            return;
        }
        
        wp_enqueue_style('kt-cimg-style', KT_CIMG_PLUGIN_URL . 'assets/kt-cimg.css', array(), KT_CIMG_VERSION);
        wp_enqueue_script('kt-cimg-script', KT_CIMG_PLUGIN_URL . 'assets/kt-cimg.js', array('jquery'), KT_CIMG_VERSION, true);
        
        wp_localize_script('kt-cimg-script', 'ktCimgAjax', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('kt_cimg_nonce')
        ));
    }
    
    public function render_settings_page() {
        ?>
        <div class="wrap kt-cimg-wrap">
            <h1>KT-CIMG Settings</h1>
            <div class="kt-cimg-card">
                <h2>Selamat Datang di KT-CIMG</h2>
                <p><strong>Developer:</strong> Rizki Adi Saputra | <a href="https://klikternak.com" target="_blank">Klikternak.com</a></p>
                <p>Plugin all-in-one untuk optimasi gambar dan database WordPress. <strong>100% GRATIS, tanpa fitur PRO!</strong></p>
                
                <h3>Fitness Utama:</h3>
                <ul>
                    <li>✅ Konversi Gambar ke WebP & AVIF (Semua Gratis)</li>
                    <li>✅ Pembersihan Database Otomatis</li>
                    <li>✅ Deteksi & Hapus Gambar Duplikat</li>
                    <li>✅ Bulk Rename Gambar</li>
                    <li>✅ Analisis Filesystem & Deteksi Orphaned Files</li>
                    <li>✅ Dukungan Page Builder (Elementor, Divi, dll)</li>
                </ul>
                
                <div class="kt-cimg-stats-overview">
                    <h3>Statistik Cepat</h3>
                    <?php
                    $upload_dir = wp_upload_dir();
                    $total_images = wp_count_attachments('attachment');
                    $total_size = $this->get_directory_size($upload_dir['basedir']);
                    ?>
                    <div class="stat-box">
                        <span class="stat-number"><?php echo number_format($total_images->images ?? 0); ?></span>
                        <span class="stat-label">Total Gambar</span>
                    </div>
                    <div class="stat-box">
                        <span class="stat-number"><?php echo size_format($total_size); ?></span>
                        <span class="stat-label">Ukuran Uploads</span>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
    
    public function render_convert_page() {
        $settings = get_option('kt_cimg_settings', array(
            'format' => 'webp',
            'quality' => 80,
            'compression' => 'lossy',
            'auto_convert' => false
        ));
        ?>
        <div class="wrap kt-cimg-wrap">
            <h1>Bulk Image Converter</h1>
            <div class="kt-cimg-card">
                <form method="post" action="" id="kt-cimg-convert-form">
                    <table class="form-table">
                        <tr>
                            <th><label for="kt_cimg_format">Format Output</label></th>
                            <td>
                                <select name="kt_cimg_format" id="kt_cimg_format">
                                    <option value="webp" <?php selected($settings['format'], 'webp'); ?>>WebP (Recommended)</option>
                                    <option value="avif" <?php selected($settings['format'], 'avif'); ?>>AVIF (Best Compression)</option>
                                </select>
                                <p class="description">Semua format tersedia GRATIS!</p>
                            </td>
                        </tr>
                        <tr>
                            <th><label for="kt_cimg_quality">Kualitas (1-100)</label></th>
                            <td>
                                <input type="number" name="kt_cimg_quality" id="kt_cimg_quality" value="<?php echo esc_attr($settings['quality']); ?>" min="1" max="100">
                            </td>
                        </tr>
                        <tr>
                            <th><label for="kt_cimg_compression">Tipe Kompresi</label></th>
                            <td>
                                <label><input type="radio" name="kt_cimg_compression" value="lossy" <?php checked($settings['compression'], 'lossy'); ?>> Lossy (Ukuran Lebih Kecil)</label><br>
                                <label><input type="radio" name="kt_cimg_compression" value="lossless" <?php checked($settings['compression'], 'lossless'); ?>> Lossless (Kualitas Maksimal)</label>
                            </td>
                        </tr>
                        <tr>
                            <th><label>Otomatis Konversi Upload</label></th>
                            <td>
                                <label><input type="checkbox" name="kt_cimg_auto_convert" <?php checked($settings['auto_convert'], true); ?>> Aktifkan konversi otomatis saat upload gambar baru</label>
                            </td>
                        </tr>
                    </table>
                    <?php wp_nonce_field('kt_cimg_save_settings', 'kt_cimg_settings_nonce'); ?>
                    <button type="button" class="button button-primary" onclick="ktCimgSaveSettings()">Simpan Pengaturan</button>
                    <hr>
                    <h3>Konversi Massal</h3>
                    <p>Konversi semua gambar di media library Anda ke format modern.</p>
                    <div id="kt-cimg-progress-container" style="display:none;">
                        <progress id="kt-cimg-progress-bar" value="0" max="100"></progress>
                        <span id="kt-cimg-progress-text">0%</span>
                        <div id="kt-cimg-log"></div>
                    </div>
                    <button type="button" class="button button-primary button-large" id="kt-cimg-start-bulk">Mulai Bulk Convert</button>
                </form>
            </div>
        </div>
        <?php
    }
    
    public function render_cleaner_page() {
        ?>
        <div class="wrap kt-cimg-wrap">
            <h1>Image Cleaner</h1>
            <div class="kt-cimg-card">
                <div class="kt-cimg-tabs">
                    <button class="tab-btn active" data-tab="duplicates">Duplikat</button>
                    <button class="tab-btn" data-tab="unused">Tidak Terpakai</button>
                </div>
                
                <div id="tab-duplicates" class="tab-content active">
                    <h3>Pindai Gambar Duplikat</h3>
                    <p>Deteksi gambar dengan konten identik berdasarkan hash file.</p>
                    <button class="button button-primary" id="kt-cimg-scan-dup">Scan Duplikat</button>
                    <div id="kt-cimg-dup-results"></div>
                </div>
                
                <div id="tab-unused" class="tab-content">
                    <h3>Pindai Gambar Tidak Terpakai</h3>
                    <p>Deteksi gambar yang tidak digunakan di postingan, halaman, atau widget.</p>
                    <button class="button button-primary" id="kt-cimg-scan-unused">Scan Unused Images</button>
                    <div id="kt-cimg-unused-results"></div>
                </div>
            </div>
        </div>
        <?php
    }
    
    public function render_rename_page() {
        ?>
        <div class="wrap kt-cimg-wrap">
            <h1>Bulk Rename Images</h1>
            <div class="kt-cimg-card">
                <form id="kt-cimg-rename-form">
                    <table class="form-table">
                        <tr>
                            <th><label for="kt_cimg_rename_pattern">Pola Penamaan</label></th>
                            <td>
                                <select name="kt_cimg_rename_pattern" id="kt_cimg_rename_pattern">
                                    <option value="sequential">Sequential (image-1, image-2)</option>
                                    <option value="post_title">Judul Postingan Terkait</option>
                                    <option value="alt_text">Alt Text Gambar</option>
                                    <option value="original">Nama Original (Sanitized)</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th><label for="kt_cimg_rename_prefix">Prefix</label></th>
                            <td>
                                <input type="text" name="kt_cimg_rename_prefix" id="kt_cimg_rename_prefix" placeholder="Contoh: site-">
                            </td>
                        </tr>
                        <tr>
                            <th><label for="kt_cimg_rename_start">Nomor Awal</label></th>
                            <td>
                                <input type="number" name="kt_cimg_rename_start" id="kt_cimg_rename_start" value="1">
                            </td>
                        </tr>
                    </table>
                    <button type="button" class="button button-primary" id="kt-cimg-preview-rename">Preview Rename</button>
                    <div id="kt-cimg-rename-preview"></div>
                    <button type="button" class="button button-primary button-large" id="kt-cimg-execute-rename" style="display:none;">Eksekusi Rename</button>
                </form>
            </div>
        </div>
        <?php
    }
    
    public function render_db_page() {
        global $wpdb;
        $stats = $this->get_db_stats();
        ?>
        <div class="wrap kt-cimg-wrap">
            <h1>Database Cleaner</h1>
            <div class="kt-cimg-card">
                <div class="kt-cimg-warning">
                    <strong>PENTING:</strong> Selalu backup database sebelum melakukan pembersihan!
                </div>
                
                <h3>Statistik Database</h3>
                <div class="kt-cimg-stats-grid">
                    <?php foreach ($stats as $table => $info): ?>
                    <div class="stat-item">
                        <strong><?php echo esc_html($table); ?></strong><br>
                        <span><?php echo number_format($info['rows']); ?> baris</span><br>
                        <span><?php echo size_format($info['size']); ?></span>
                    </div>
                    <?php endforeach; ?>
                </div>
                
                <h3>Pembersihan</h3>
                <form id="kt-cimg-db-clean-form">
                    <label><input type="checkbox" name="clean_revisions" checked> Post Revisions</label><br>
                    <label><input type="checkbox" name="clean_spam" checked> Spam Comments</label><br>
                    <label><input type="checkbox" name="clean_trash" checked> Trashed Posts/Comments</label><br>
                    <label><input type="checkbox" name="clean_transients" checked> Transients</label><br>
                    <label><input type="checkbox" name="clean_orphan_meta" checked> Orphaned Metadata</label><br>
                    <br>
                    <button type="button" class="button button-primary button-large" id="kt-cimg-clean-db">Bersihkan Database</button>
                </form>
                <div id="kt-cimg-db-result"></div>
            </div>
        </div>
        <?php
    }
    
    public function render_fs_page() {
        ?>
        <div class="wrap kt-cimg-wrap">
            <h1>Filesystem Analysis</h1>
            <div class="kt-cimg-card">
                <p>Analisis direktori uploads fisik dan cocokkan dengan Media Library WordPress.</p>
                <p>Mendukung deteksi untuk: Elementor, Divi, WPBakery, Beaver Builder, dan lainnya.</p>
                
                <button class="button button-primary" id="kt-cimg-scan-fs">Scan Filesystem</button>
                <div id="kt-cimg-fs-results"></div>
            </div>
        </div>
        <?php
    }
    
    // AJAX Handlers
    public function ajax_save_settings() {
        check_ajax_referer('kt_cimg_nonce', 'nonce');
        
        $settings = array(
            'format' => sanitize_text_field($_POST['format']),
            'quality' => intval($_POST['quality']),
            'compression' => sanitize_text_field($_POST['compression']),
            'auto_convert' => isset($_POST['auto_convert']) ? true : false
        );
        
        update_option('kt_cimg_settings', $settings);
        wp_send_json_success(array('message' => 'Settings saved successfully'));
    }
    
    public function ajax_convert_bulk() {
        check_ajax_referer('kt_cimg_nonce', 'nonce');
        
        $settings = get_option('kt_cimg_settings', array());
        $format = isset($_POST['format']) ? sanitize_text_field($_POST['format']) : ($settings['format'] ?? 'webp');
        $quality = isset($_POST['quality']) ? intval($_POST['quality']) : ($settings['quality'] ?? 80);
        $compression = isset($_POST['compression']) ? sanitize_text_field($_POST['compression']) : ($settings['compression'] ?? 'lossy');
        $offset = isset($_POST['offset']) ? intval($_POST['offset']) : 0;
        $batch_size = 10;
        
        $args = array(
            'post_type' => 'attachment',
            'post_mime_type' => array('image/jpeg', 'image/png', 'image/gif'),
            'posts_per_page' => $batch_size,
            'offset' => $offset,
            'fields' => 'ids'
        );
        
        $attachments = get_posts($args);
        $total_processed = 0;
        $log = array();
        
        foreach ($attachments as $att_id) {
            $result = $this->convert_image($att_id, $format, $quality, $compression);
            if ($result['success']) {
                $total_processed++;
                $log[] = "Converted: " . get_the_title($att_id);
            }
        }
        
        wp_send_json_success(array(
            'processed' => $total_processed,
            'offset' => $offset + $batch_size,
            'has_more' => count($attachments) == $batch_size,
            'log' => $log
        ));
    }
    
    public function convert_image($attachment_id, $format = 'webp', $quality = 80, $compression = 'lossy') {
        $file_path = get_attached_file($attachment_id);
        if (!file_exists($file_path)) {
            return array('success' => false, 'message' => 'File not found');
        }
        
        $mime_type = get_post_mime_type($attachment_id);
        $allowed_mimes = array('image/jpeg', 'image/png', 'image/gif');
        if (!in_array($mime_type, $allowed_mimes)) {
            return array('success' => false, 'message' => 'Unsupported mime type');
        }
        
        $backup_path = KT_CIMG_BACKUP_DIR . basename($file_path) . '.bak';
        if (!file_exists($backup_path)) {
            copy($file_path, $backup_path);
        }
        
        $output_path = preg_replace('/\.' . pathinfo($file_path, PATHINFO_EXTENSION) . '$/i', '.' . $format, $file_path);
        
        if ($format === 'webp') {
            $success = $this->convert_to_webp($file_path, $output_path, $quality, $compression === 'lossless');
        } elseif ($format === 'avif') {
            $success = $this->convert_to_avif($file_path, $output_path, $quality);
        } else {
            return array('success' => false, 'message' => 'Invalid format');
        }
        
        if ($success) {
            $new_mime = 'image/' . $format;
            $new_attachment = array(
                'ID' => $attachment_id,
                'post_mime_type' => $new_mime
            );
            wp_update_attachment_metadata($attachment_id, wp_generate_attachment_metadata($attachment_id, $output_path));
            
            return array('success' => true, 'message' => 'Conversion successful');
        }
        
        return array('success' => false, 'message' => 'Conversion failed');
    }
    
    private function convert_to_webp($input, $output, $quality, $lossless) {
        if (!function_exists('imagewebp')) {
            return false;
        }
        
        $mime = mime_content_type($input);
        if ($mime === 'image/jpeg') {
            $image = imagecreatefromjpeg($input);
        } elseif ($mime === 'image/png') {
            $image = imagecreatefrompng($input);
        } elseif ($mime === 'image/gif') {
            $image = imagecreatefromgif($input);
        } else {
            return false;
        }
        
        if (!$image) {
            return false;
        }
        
        imagealphablending($image, false);
        imagesavealpha($image, true);
        
        $result = imagewebp($image, $output, $quality);
        imagedestroy($image);
        
        return $result;
    }
    
    private function convert_to_avif($input, $output, $quality) {
        if (!function_exists('imageavif')) {
            return false;
        }
        
        $mime = mime_content_type($input);
        if ($mime === 'image/jpeg') {
            $image = imagecreatefromjpeg($input);
        } elseif ($mime === 'image/png') {
            $image = imagecreatefrompng($input);
        } elseif ($mime === 'image/gif') {
            $image = imagecreatefromgif($input);
        } else {
            return false;
        }
        
        if (!$image) {
            return false;
        }
        
        $result = imageavif($image, $output, $quality);
        imagedestroy($image);
        
        return $result;
    }
    
    public function ajax_scan_duplicates() {
        check_ajax_referer('kt_cimg_nonce', 'nonce');
        
        $args = array(
            'post_type' => 'attachment',
            'post_mime_type' => array('image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/avif'),
            'posts_per_page' => -1,
            'fields' => 'ids'
        );
        
        $attachments = get_posts($args);
        $hashes = array();
        $duplicates = array();
        
        foreach ($attachments as $att_id) {
            $file_path = get_attached_file($att_id);
            if (!file_exists($file_path)) continue;
            
            $hash = md5_file($file_path);
            if (isset($hashes[$hash])) {
                $duplicates[$hash][] = $att_id;
            } else {
                $hashes[$hash] = array($att_id);
            }
        }
        
        $dup_groups = array_filter($duplicates, function($group) {
            return count($group) > 1;
        });
        
        wp_send_json_success(array('duplicates' => $dup_groups, 'count' => count($dup_groups)));
    }
    
    public function ajax_delete_duplicates() {
        check_ajax_referer('kt_cimg_nonce', 'nonce');
        
        $keep_ids = isset($_POST['keep_ids']) ? array_map('intval', $_POST['keep_ids']) : array();
        $delete_ids = isset($_POST['delete_ids']) ? array_map('intval', $_POST['delete_ids']) : array();
        
        $deleted = 0;
        foreach ($delete_ids as $id) {
            if (!in_array($id, $keep_ids) && wp_delete_attachment($id, true)) {
                $deleted++;
            }
        }
        
        wp_send_json_success(array('deleted' => $deleted));
    }
    
    public function ajax_scan_unused() {
        check_ajax_referer('kt_cimg_nonce', 'nonce');
        
        global $wpdb;
        $all_images = $wpdb->get_col("SELECT ID FROM $wpdb->posts WHERE post_type = 'attachment' AND post_mime_type LIKE 'image/%'");
        
        $used_ids = array();
        $content_check = $wpdb->get_col("SELECT post_content FROM $wpdb->posts WHERE post_type IN ('post', 'page')");
        $content_check = implode(' ', $content_check);
        
        foreach ($all_images as $id) {
            $file_url = wp_get_attachment_url($id);
            $file_name = basename($file_url);
            
            if (strpos($content_check, $file_name) !== false || strpos($content_check, $file_url) !== false) {
                $used_ids[] = $id;
            }
        }
        
        $unused_ids = array_diff($all_images, $used_ids);
        
        wp_send_json_success(array('unused' => $unused_ids, 'count' => count($unused_ids)));
    }
    
    public function ajax_delete_unused() {
        check_ajax_referer('kt_cimg_nonce', 'nonce');
        
        $delete_ids = isset($_POST['delete_ids']) ? array_map('intval', $_POST['delete_ids']) : array();
        $deleted = 0;
        
        foreach ($delete_ids as $id) {
            if (wp_delete_attachment($id, true)) {
                $deleted++;
            }
        }
        
        wp_send_json_success(array('deleted' => $deleted));
    }
    
    public function ajax_preview_rename() {
        check_ajax_referer('kt_cimg_nonce', 'nonce');
        
        $pattern = sanitize_text_field($_POST['pattern']);
        $prefix = sanitize_text_field($_POST['prefix']);
        $start_num = intval($_POST['start']);
        
        $args = array(
            'post_type' => 'attachment',
            'post_mime_type' => array('image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/avif'),
            'posts_per_page' => 20,
            'fields' => 'ids'
        );
        
        $attachments = get_posts($args);
        $preview = array();
        $counter = $start_num;
        
        foreach ($attachments as $id) {
            $old_name = get_the_title($id);
            $new_name = '';
            
            switch ($pattern) {
                case 'sequential':
                    $new_name = $prefix . 'image-' . $counter;
                    $counter++;
                    break;
                case 'post_title':
                    $parent = wp_get_post_parent_id($id);
                    if ($parent) {
                        $new_name = sanitize_title(get_the_title($parent));
                    } else {
                        $new_name = sanitize_title($old_name);
                    }
                    break;
                case 'alt_text':
                    $alt = get_post_meta($id, '_wp_attachment_image_alt', true);
                    $new_name = $alt ? sanitize_title($alt) : sanitize_title($old_name);
                    break;
                case 'original':
                    $new_name = sanitize_title(pathinfo(get_attached_file($id), PATHINFO_FILENAME));
                    break;
            }
            
            $preview[] = array(
                'id' => $id,
                'old' => $old_name,
                'new' => $new_name
            );
        }
        
        wp_send_json_success(array('preview' => $preview));
    }
    
    public function ajax_execute_rename() {
        check_ajax_referer('kt_cimg_nonce', 'nonce');
        
        $renames = isset($_POST['renames']) ? $_POST['renames'] : array();
        $updated = 0;
        
        foreach ($renames as $rename) {
            $id = intval($rename['id']);
            $new_name = sanitize_title($rename['new']);
            
            if ($id && $new_name) {
                wp_update_post(array(
                    'ID' => $id,
                    'post_title' => $new_name
                ));
                $updated++;
            }
        }
        
        wp_send_json_success(array('updated' => $updated));
    }
    
    public function get_db_stats() {
        global $wpdb;
        $stats = array();
        
        $tables = $wpdb->get_results("SHOW TABLE STATUS", ARRAY_A);
        foreach ($tables as $table) {
            if (strpos($table['Name'], $wpdb->prefix) === 0) {
                $stats[str_replace($wpdb->prefix, '', $table['Name'])] = array(
                    'rows' => $table['Rows'],
                    'size' => $table['Data_length'] + $table['Index_length']
                );
            }
        }
        
        return $stats;
    }
    
    public function ajax_analyze_db() {
        check_ajax_referer('kt_cimg_nonce', 'nonce');
        $stats = $this->get_db_stats();
        wp_send_json_success(array('stats' => $stats));
    }
    
    public function ajax_clean_database() {
        check_ajax_referer('kt_cimg_nonce', 'nonce');
        
        global $wpdb;
        $cleaned = array();
        
        if (isset($_POST['clean_revisions'])) {
            $revisions = $wpdb->query("DELETE FROM $wpdb->posts WHERE post_type = 'revision'");
            $cleaned['revisions'] = $revisions;
        }
        
        if (isset($_POST['clean_spam'])) {
            $spam = $wpdb->query("DELETE FROM $wpdb->comments WHERE comment_approved = 'spam'");
            $cleaned['spam'] = $spam;
        }
        
        if (isset($_POST['clean_trash'])) {
            $trash = $wpdb->query("DELETE FROM $wpdb->posts WHERE post_status = 'trash'");
            $trash .= $wpdb->query("DELETE FROM $wpdb->comments WHERE comment_approved = 'trash'");
            $cleaned['trash'] = $trash;
        }
        
        if (isset($_POST['clean_transients'])) {
            $transients = $wpdb->query("DELETE FROM $wpdb->options WHERE option_name LIKE '_transient_%' OR option_name LIKE '_site_transient_%'");
            $cleaned['transients'] = $transients;
        }
        
        if (isset($_POST['clean_orphan_meta'])) {
            $orphan = $wpdb->query("DELETE pm FROM $wpdb->postmeta pm LEFT JOIN $wpdb->posts wp ON wp.ID = pm.post_id WHERE wp.ID IS NULL");
            $cleaned['orphan_meta'] = $orphan;
        }
        
        wp_send_json_success(array('cleaned' => $cleaned));
    }
    
    public function ajax_scan_filesystem() {
        check_ajax_referer('kt_cimg_nonce', 'nonce');
        
        $upload_dir = wp_upload_dir();
        $files = $this->scan_directory($upload_dir['basedir']);
        
        global $wpdb;
        $db_files = $wpdb->get_col("SELECT meta_value FROM $wpdb->postmeta WHERE meta_key = '_wp_attached_file'");
        
        $orphaned = array_diff($files, $db_files);
        $matched = array_intersect($files, $db_files);
        
        wp_send_json_success(array(
            'total_files' => count($files),
            'matched' => count($matched),
            'orphaned' => count($orphaned),
            'orphaned_list' => array_slice($orphaned, 0, 50)
        ));
    }
    
    private function scan_directory($dir) {
        $files = array();
        if (!is_dir($dir)) return $files;
        
        $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
        foreach ($iterator as $file) {
            if ($file->isFile() && preg_match('/\.(jpg|jpeg|png|gif|webp|avif|svg)$/i', $file->getFilename())) {
                $rel_path = str_replace(wp_upload_dir()['basedir'] . '/', '', $file->getPathname());
                $files[] = $rel_path;
            }
        }
        
        return $files;
    }
    
    public function ajax_delete_orphaned() {
        check_ajax_referer('kt_cimg_nonce', 'nonce');
        
        $files = isset($_POST['files']) ? $_POST['files'] : array();
        $upload_dir = wp_upload_dir();
        $deleted = 0;
        
        foreach ($files as $file) {
            $full_path = $upload_dir['basedir'] . '/' . $file;
            if (file_exists($full_path) && unlink($full_path)) {
                $deleted++;
            }
        }
        
        wp_send_json_success(array('deleted' => $deleted));
    }
    
    public function convert_on_upload($metadata, $attachment_id) {
        $settings = get_option('kt_cimg_settings', array());
        if (empty($settings['auto_convert'])) {
            return $metadata;
        }
        
        $format = $settings['format'] ?? 'webp';
        $quality = $settings['quality'] ?? 80;
        $compression = $settings['compression'] ?? 'lossy';
        
        $this->convert_image($attachment_id, $format, $quality, $compression);
        
        return $metadata;
    }
    
    public function serve_modern_images($content) {
        preg_match_all('/<img[^>]+src="([^"]+)"[^>]*>/i', $content, $matches);
        
        if (empty($matches[1])) {
            return $content;
        }
        
        foreach ($matches[1] as $img_src) {
            $modern_src = preg_replace('/\.(jpg|jpeg|png|gif)$/i', '.webp', $img_src);
            $upload_dir = wp_upload_dir();
            
            if (file_exists($upload_dir['basedir'] . '/' . str_replace($upload_dir['baseurl'] . '/', '', $modern_src))) {
                $content = str_replace($img_src, $modern_src, $content);
            }
        }
        
        return $content;
    }
    
    private function get_directory_size($directory) {
        $size = 0;
        if (!is_dir($directory)) return 0;
        
        foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directory, RecursiveDirectoryIterator::SKIP_DOTS)) as $file) {
            if ($file->isFile()) {
                $size += $file->getSize();
            }
        }
        
        return $size;
    }
}

// Initialize plugin
function kt_cimg_init() {
    KT_CIMG_Main::get_instance();
}
add_action('plugins_loaded', 'kt_cimg_init');
