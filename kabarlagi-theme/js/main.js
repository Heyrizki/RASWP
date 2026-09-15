/**
 * Main JavaScript for Kabar Lagi Theme
 */

(function($) {
    'use strict';

    // Dark Mode Toggle
    function initDarkMode() {
        const toggle = $('.dark-mode-toggle');
        const body = $('body');
        
        // Check for saved theme preference
        const savedTheme = localStorage.getItem('kabarlagi-theme');
        if (savedTheme) {
            body.attr('data-theme', savedTheme);
        } else if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
            // Auto dark mode based on system preference
            if (body.hasClass('auto-dark-mode')) {
                body.attr('data-theme', 'dark');
            }
        }
        
        toggle.on('click', function() {
            const currentTheme = body.attr('data-theme');
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
            
            body.attr('data-theme', newTheme);
            localStorage.setItem('kabarlagi-theme', newTheme);
            
            // Send notification
            if (Notification.permission === 'granted') {
                new Notification('Mode diubah', {
                    body: 'Mode ' + (newTheme === 'dark' ? 'gelap' : 'terang') + ' diaktifkan',
                    icon: '/wp-content/themes/kabarlagi/images/icon.png'
                });
            }
        });
    }

    // Mobile Menu Toggle
    function initMobileMenu() {
        const menuToggle = $('.mobile-menu-toggle');
        const mobileMenu = $('.main-navigation ul');
        
        menuToggle.on('click', function() {
            mobileMenu.slideToggle(300);
            $(this).toggleClass('active');
        });
    }

    // Breaking News Marquee
    function initMarquee() {
        const marquee = $('.marquee-content');
        if (marquee.length) {
            // Clone content for seamless loop
            marquee.append(marquee.html());
        }
    }

    // Load More Functionality
    function initLoadMore() {
        const loadMoreBtn = $('.load-more-btn');
        let currentPage = 1;
        
        loadMoreBtn.on('click', function() {
            const btn = $(this);
            const nonce = btn.data('nonce');
            const category = btn.data('category') || '';
            
            btn.prop('disabled', true).text('Loading...');
            
            $.ajax({
                url: kabarlagi_ajax.ajax_url,
                type: 'POST',
                data: {
                    action: 'kabarlagi_load_more',
                    page: currentPage + 1,
                    category: category,
                    nonce: nonce
                },
                success: function(response) {
                    if (response) {
                        $('.post-grid').append(response);
                        currentPage++;
                        btn.prop('disabled', false).text('Load More Articles');
                        
                        // Check if no more posts
                        if (response.trim() === '') {
                            btn.hide();
                        }
                    } else {
                        btn.hide();
                    }
                },
                error: function() {
                    btn.prop('disabled', false).text('Error - Try Again');
                }
            });
        });
    }

    // Smooth Scroll
    function initSmoothScroll() {
        $('a[href^="#"]').on('click', function(e) {
            const target = $(this.getAttribute('href'));
            if (target.length) {
                e.preventDefault();
                $('html, body').animate({
                    scrollTop: target.offset().top - 100
                }, 800);
            }
        });
    }

    // Sticky Header
    function initStickyHeader() {
        const header = $('.site-header');
        const scrollThreshold = 200;
        
        $(window).on('scroll', function() {
            if ($(window).scrollTop() > scrollThreshold) {
                header.addClass('sticky');
            } else {
                header.removeClass('sticky');
            }
        });
    }

    // Post Views Tracking (prevent duplicate views)
    function trackPostView() {
        if ($('.single-post').length) {
            const postId = $('.single-post').data('post-id');
            const hasViewed = sessionStorage.getItem('viewed_post_' + postId);
            
            if (!hasViewed && postId) {
                sessionStorage.setItem('viewed_post_' + postId, 'true');
                
                $.ajax({
                    url: kabarlagi_ajax.ajax_url,
                    type: 'POST',
                    data: {
                        action: 'kabarlagi_track_view',
                        post_id: postId
                    }
                });
            }
        }
    }

    // Social Share Counter (optional)
    function initSocialShare() {
        $('.social-share a').on('click', function(e) {
            // Track share event
            if (typeof gtag === 'function') {
                gtag('event', 'share', {
                    method: $(this).attr('class'),
                    content_type: 'article',
                    item_id: window.location.href
                });
            }
        });
    }

    // Gallery Slideshow
    function initGallery() {
        const galleries = $('.gallery-slideshow');
        
        galleries.each(function() {
            const gallery = $(this);
            let currentIndex = 0;
            const images = gallery.find('img');
            
            // Auto-advance slideshow
            setInterval(function() {
                currentIndex = (currentIndex + 1) % images.length;
                gallery.animate({
                    scrollLeft: images.eq(currentIndex).position().left
                }, 500);
            }, 5000);
        });
    }

    // Search Form Enhancement
    function initSearch() {
        const searchForm = $('.search-form');
        const searchInput = searchForm.find('input[type="search"]');
        
        searchInput.on('focus', function() {
            searchForm.addClass('active');
        }).on('blur', function() {
            searchForm.removeClass('active');
        });
    }

    // Comment Notification Request
    function initCommentNotification() {
        if ('Notification' in window && Notification.permission === 'default') {
            $('.comment-form').on('submit', function() {
                Notification.requestPermission();
            });
        }
    }

    // Reading Progress Bar
    function initReadingProgress() {
        if ($('.single-post').length) {
            const progressBar = $('<div class="reading-progress"></div>');
            $('body').prepend(progressBar);
            
            $(window).on('scroll', function() {
                const windowHeight = $(window).height();
                const documentHeight = $(document).height();
                const scrollTop = $(window).scrollTop();
                const progress = (scrollTop / (documentHeight - windowHeight)) * 100;
                
                progressBar.css('width', progress + '%');
            });
        }
    }

    // Image Lazy Loading
    function initLazyLoad() {
        const images = $('img[data-src]');
        
        const imageObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    img.src = img.dataset.src;
                    img.removeAttribute('data-src');
                    observer.unobserve(img);
                }
            });
        });
        
        images.each(function() {
            imageObserver.observe(this);
        });
    }

    // Initialize all functions when DOM is ready
    $(document).ready(function() {
        initDarkMode();
        initMobileMenu();
        initMarquee();
        initLoadMore();
        initSmoothScroll();
        initStickyHeader();
        trackPostView();
        initSocialShare();
        initGallery();
        initSearch();
        initCommentNotification();
        initReadingProgress();
        initLazyLoad();
    });

    // Handle window resize
    $(window).on('resize', function() {
        // Reset mobile menu on resize
        if ($(window).width() > 768) {
            $('.main-navigation ul').show();
            $('.mobile-menu-toggle').removeClass('active');
        }
    });

})(jQuery);
