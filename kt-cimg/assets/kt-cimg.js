jQuery(document).ready(function($) {
    
    // Tab Switching
    $('.tab-btn').on('click', function() {
        var tabId = $(this).data('tab');
        $('.tab-btn').removeClass('active');
        $('.tab-content').removeClass('active');
        $(this).addClass('active');
        $('#tab-' + tabId).addClass('active');
    });
    
    // Save Settings
    window.ktCimgSaveSettings = function() {
        var format = $('#kt_cimg_format').val();
        var quality = $('#kt_cimg_quality').val();
        var compression = $('input[name="kt_cimg_compression"]:checked').val();
        var autoConvert = $('input[name="kt_cimg_auto_convert"]').is(':checked');
        
        $.post(ktCimgAjax.ajax_url, {
            action: 'kt_cimg_save_settings',
            nonce: ktCimgAjax.nonce,
            format: format,
            quality: quality,
            compression: compression,
            auto_convert: autoConvert
        }, function(response) {
            alert('Pengaturan berhasil disimpan!');
        });
    };
    
    // Bulk Convert
    $('#kt-cimg-start-bulk').on('click', function() {
        var format = $('#kt_cimg_format').val();
        var quality = $('#kt_cimg_quality').val();
        var compression = $('input[name="kt_cimg_compression"]:checked').val();
        var offset = 0;
        var totalProcessed = 0;
        
        $('#kt-cimg-progress-container').show();
        $(this).prop('disabled', true);
        
        function processBatch() {
            $.post(ktCimgAjax.ajax_url, {
                action: 'kt_cimg_convert_bulk',
                nonce: ktCimgAjax.nonce,
                format: format,
                quality: quality,
                compression: compression,
                offset: offset
            }, function(response) {
                if (response.success) {
                    totalProcessed += response.data.processed;
                    offset = response.data.offset;
                    
                    var progress = Math.min(100, Math.round((totalProcessed / (offset + 10)) * 100));
                    $('#kt-cimg-progress-bar').val(progress);
                    $('#kt-cimg-progress-text').text(progress + '%');
                    
                    response.data.log.forEach(function(log) {
                        $('#kt-cimg-log').append('<div>' + log + '</div>');
                    });
                    
                    if (response.data.has_more) {
                        setTimeout(processBatch, 500);
                    } else {
                        $('#kt-cimg-log').append('<div><strong>Selesai! Total dikonversi: ' + totalProcessed + '</strong></div>');
                        $('#kt-cimg-start-bulk').prop('disabled', false);
                    }
                }
            });
        }
        
        processBatch();
    });
    
    // Scan Duplicates
    $('#kt-cimg-scan-dup').on('click', function() {
        var $btn = $(this);
        $btn.prop('disabled', true).text('Scanning...');
        
        $.post(ktCimgAjax.ajax_url, {
            action: 'kt_cimg_scan_duplicates',
            nonce: ktCimgAjax.nonce
        }, function(response) {
            $btn.prop('disabled', false).text('Scan Duplikat');
            
            if (response.success && response.data.count > 0) {
                var html = '<div class="kt-cimg-result-success">Ditemukan ' + response.data.count + ' grup duplikat!</div>';
                
                $.each(response.data.duplicates, function(hash, ids) {
                    html += '<div class="duplicate-group">';
                    html += '<p><strong>Duplikat Group:</strong></p>';
                    html += '<div class="duplicate-images">';
                    
                    ids.forEach(function(id, index) {
                        var imgClass = index === 0 ? 'keep' : 'delete';
                        var keepRadio = index === 0 ? 'checked' : '';
                        html += '<div style="text-align:center;">';
                        html += '<img src="' + ktCimgAjax.ajax_url.replace('admin-ajax.php', '') + '?action=get_attachment_image&ids=' + id + '" alt="Image ' + id + '">';
                        html += '<br><label><input type="radio" name="keep_' + hash + '" value="' + id + '" ' + keepRadio + '> Simpan</label>';
                        html += '</div>';
                    });
                    
                    html += '</div></div>';
                });
                
                html += '<button class="button button-primary" id="kt-cimg-delete-dup-btn">Hapus Duplikat Terpilih</button>';
                $('#kt-cimg-dup-results').html(html);
                
                $('#kt-cimg-delete-dup-btn').on('click', function() {
                    var keepIds = [];
                    $('input[type="radio"]:checked').each(function() {
                        keepIds.push(parseInt($(this).val()));
                    });
                    
                    var allIds = [];
                    $.each(response.data.duplicates, function(hash, ids) {
                        allIds = allIds.concat(ids);
                    });
                    
                    var deleteIds = allIds.filter(id => !keepIds.includes(id));
                    
                    $.post(ktCimgAjax.ajax_url, {
                        action: 'kt_cimg_delete_duplicates',
                        nonce: ktCimgAjax.nonce,
                        keep_ids: keepIds,
                        delete_ids: deleteIds
                    }, function(delResponse) {
                        if (delResponse.success) {
                            alert('Berhasil menghapus ' + delResponse.data.deleted + ' gambar duplikat!');
                            location.reload();
                        }
                    });
                });
            } else {
                $('#kt-cimg-dup-results').html('<div class="kt-cimg-result-success">Tidak ada duplikat ditemukan!</div>');
            }
        });
    });
    
    // Scan Unused Images
    $('#kt-cimg-scan-unused').on('click', function() {
        var $btn = $(this);
        $btn.prop('disabled', true).text('Scanning...');
        
        $.post(ktCimgAjax.ajax_url, {
            action: 'kt_cimg_scan_unused',
            nonce: ktCimgAjax.nonce
        }, function(response) {
            $btn.prop('disabled', false).text('Scan Unused Images');
            
            if (response.success && response.data.count > 0) {
                var html = '<div class="kt-cimg-result-success">Ditemukan ' + response.data.count + ' gambar tidak terpakai!</div>';
                html += '<div id="unused-list">';
                
                response.data.unused.slice(0, 50).forEach(function(id) {
                    html += '<div class="unused-item">';
                    html += '<label><input type="checkbox" class="unused-check" value="' + id + '"> ';
                    html += 'ID: ' + id + '</label>';
                    html += '</div>';
                });
                
                html += '</div>';
                html += '<button class="button button-primary" id="kt-cimg-delete-unused-btn">Hapus Gambar Terpilih</button>';
                $('#kt-cimg-unused-results').html(html);
                
                $('#kt-cimg-delete-unused-btn').on('click', function() {
                    var deleteIds = [];
                    $('.unused-check:checked').each(function() {
                        deleteIds.push(parseInt($(this).val()));
                    });
                    
                    if (deleteIds.length === 0) {
                        alert('Pilih minimal satu gambar!');
                        return;
                    }
                    
                    if (!confirm('Yakin ingin menghapus ' + deleteIds.length + ' gambar?')) {
                        return;
                    }
                    
                    $.post(ktCimgAjax.ajax_url, {
                        action: 'kt_cimg_delete_unused',
                        nonce: ktCimgAjax.nonce,
                        delete_ids: deleteIds
                    }, function(delResponse) {
                        if (delResponse.success) {
                            alert('Berhasil menghapus ' + delResponse.data.deleted + ' gambar!');
                            location.reload();
                        }
                    });
                });
            } else {
                $('#kt-cimg-unused-results').html('<div class="kt-cimg-result-success">Semua gambar sedang digunakan!</div>');
            }
        });
    });
    
    // Preview Rename
    $('#kt-cimg-preview-rename').on('click', function() {
        var pattern = $('#kt_cimg_rename_pattern').val();
        var prefix = $('#kt_cimg_rename_prefix').val();
        var start = $('#kt_cimg_rename_start').val();
        
        $.post(ktCimgAjax.ajax_url, {
            action: 'kt_cimg_preview_rename',
            nonce: ktCimgAjax.nonce,
            pattern: pattern,
            prefix: prefix,
            start: start
        }, function(response) {
            if (response.success) {
                var html = '<h4>Preview Rename:</h4>';
                html += '<table class="widefat">';
                html += '<thead><tr><th>ID</th><th>Nama Lama</th><th>Nama Baru</th></tr></thead><tbody>';
                
                response.data.preview.forEach(function(item) {
                    html += '<tr>';
                    html += '<td>' + item.id + '</td>';
                    html += '<td>' + item.old + '</td>';
                    html += '<td>' + item.new + '</td>';
                    html += '</tr>';
                });
                
                html += '</tbody></table>';
                html += '<input type="hidden" id="kt-cimg-renames-data" value=\'' + JSON.stringify(response.data.preview) + '\'>';
                html += '<button class="button button-primary button-large" id="kt-cimg-execute-rename" style="margin-top:15px;">Eksekusi Rename</button>';
                
                $('#kt-cimg-rename-preview').html(html);
            }
        });
    });
    
    // Execute Rename (delegated event)
    $(document).on('click', '#kt-cimg-execute-rename', function() {
        var renames = JSON.parse($('#kt-cimg-renames-data').val());
        
        if (!confirm('Yakin ingin mengubah nama ' + renames.length + ' gambar?')) {
            return;
        }
        
        $.post(ktCimgAjax.ajax_url, {
            action: 'kt_cimg_execute_rename',
            nonce: ktCimgAjax.nonce,
            renames: renames
        }, function(response) {
            if (response.success) {
                alert('Berhasil mengubah nama ' + response.data.updated + ' gambar!');
                location.reload();
            }
        });
    });
    
    // Clean Database
    $('#kt-cimg-clean-db').on('click', function() {
        if (!confirm('PERINGATAN: Pastikan Anda sudah backup database! Lanjutkan pembersihan?')) {
            return;
        }
        
        var $btn = $(this);
        $btn.prop('disabled', true).text('Cleaning...');
        
        var data = {
            action: 'kt_cimg_clean_database',
            nonce: ktCimgAjax.nonce
        };
        
        $('#kt-cimg-db-clean-form input[type="checkbox"]:checked').each(function() {
            data[$(this).attr('name')] = true;
        });
        
        $.post(ktCimgAjax.ajax_url, data, function(response) {
            $btn.prop('disabled', false).text('Bersihkan Database');
            
            if (response.success) {
                var html = '<div class="kt-cimg-result-success"><strong>Berhasil membersihkan database!</strong><br>';
                $.each(response.data.cleaned, function(type, count) {
                    html += type + ': ' + count + ' baris<br>';
                });
                html += '</div>';
                $('#kt-cimg-db-result').html(html);
            }
        });
    });
    
    // Scan Filesystem
    $('#kt-cimg-scan-fs').on('click', function() {
        var $btn = $(this);
        $btn.prop('disabled', true).text('Scanning...');
        
        $.post(ktCimgAjax.ajax_url, {
            action: 'kt_cimg_scan_filesystem',
            nonce: ktCimgAjax.nonce
        }, function(response) {
            $btn.prop('disabled', false).text('Scan Filesystem');
            
            if (response.success) {
                var html = '<div class="fs-stat-box">';
                html += '<strong>Total Files:</strong> ' + response.data.total_files + '<br>';
                html += '<strong>Matched with DB:</strong> ' + response.data.matched + '<br>';
                html += '<strong>Orphaned Files:</strong> ' + response.data.orphaned;
                html += '</div>';
                
                if (response.data.orphaned > 0) {
                    html += '<div class="kt-cimg-warning">Ditemukan ' + response.data.orphaned + ' file orphaned (tidak ada di database)!</div>';
                    html += '<div class="orphaned-file-list">';
                    
                    response.data.orphaned_list.forEach(function(file) {
                        html += '<div><label><input type="checkbox" class="orphan-check" value="' + file + '"> ' + file + '</label></div>';
                    });
                    
                    html += '</div>';
                    html += '<button class="button button-primary" id="kt-cimg-delete-orphan-btn">Hapus Orphaned Files</button>';
                } else {
                    html += '<div class="kt-cimg-result-success">Tidak ada file orphaned ditemukan!</div>';
                }
                
                $('#kt-cimg-fs-results').html(html);
                
                $('#kt-cimg-delete-orphan-btn').on('click', function() {
                    var files = [];
                    $('.orphan-check:checked').each(function() {
                        files.push($(this).val());
                    });
                    
                    if (files.length === 0) {
                        alert('Pilih minimal satu file!');
                        return;
                    }
                    
                    if (!confirm('Yakin ingin menghapus ' + files.length + ' file orphaned?')) {
                        return;
                    }
                    
                    $.post(ktCimgAjax.ajax_url, {
                        action: 'kt_cimg_delete_orphaned',
                        nonce: ktCimgAjax.nonce,
                        files: files
                    }, function(delResponse) {
                        if (delResponse.success) {
                            alert('Berhasil menghapus ' + delResponse.data.deleted + ' file!');
                            location.reload();
                        }
                    });
                });
            }
        });
    });
    
});
