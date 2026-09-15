/**
 * Load More functionality for Kabar Lagi Theme
 */

(function($) {
    'use strict';
    
    $(document).ready(function() {
        var loadMoreBtn = $('.load-more-btn');
        var currentPage = 1;
        var isLoading = false;
        
        if (loadMoreBtn.length) {
            loadMoreBtn.on('click', function() {
                if (isLoading) return;
                
                var btn = $(this);
                var nonce = btn.data('nonce');
                var category = btn.data('category') || '';
                
                isLoading = true;
                btn.prop('disabled', true).text('Loading...');
                
                $.ajax({
                    url: typeof kabarlagi_ajax !== 'undefined' ? kabarlagi_ajax.ajax_url : '/wp-admin/admin-ajax.php',
                    type: 'POST',
                    data: {
                        action: 'kabarlagi_load_more',
                        page: currentPage + 1,
                        category: category,
                        nonce: nonce
                    },
                    success: function(response) {
                        isLoading = false;
                        
                        if (response && response.trim() !== '') {
                            $('.post-grid').append(response);
                            currentPage++;
                            btn.prop('disabled', false).text('Load More Articles');
                            
                            // Re-initialize lazy loading for new images
                            if (typeof initLazyLoad === 'function') {
                                initLazyLoad();
                            }
                        } else {
                            btn.hide();
                        }
                    },
                    error: function() {
                        isLoading = false;
                        btn.prop('disabled', false).text('Error - Try Again');
                    }
                });
            });
            
            // Infinite scroll option
            if (loadMoreBtn.data('infinite') === 'true') {
                $(window).on('scroll', function() {
                    if ($(window).scrollTop() + $(window).height() >= $(document).height() - 500) {
                        if (!isLoading && loadMoreBtn.is(':visible')) {
                            loadMoreBtn.trigger('click');
                        }
                    }
                });
            }
        }
    });
    
})(jQuery);
