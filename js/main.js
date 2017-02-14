/* jshint browser: true, devel: true, indent: 2, curly: true, eqeqeq: true, futurehostile: true, latedef: true, undef: true, unused: true */
/* global $, document, Site, imagesLoaded, Swiper, zenscroll */
var Shuffle = window.shuffle;

Site = {
  mobileThreshold: 601,
  animationSpeed: 400,
  swiperVariables: {
    speed: 400,
    pagination: '.swiper-pagination',
    paginationType: 'fraction',
    loop: true,
    nextButton: '.swiper-button-next',
    prevButton: '.swiper-button-prev',
  },
  init: function() {
    var _this = this;

    $(window).resize(function(){
      _this.onResize();
    });

    $(document).ready(function () {
      Site.Galleries.init();
    });

    _this.fixWidows();
    _this.bindPinScroll();

    Site.Shuffle.init();
    Site.Media.init();
    Site.Lightbox.init();
    Site.Luminaries.init();
    Site.Expandables.init();
    Site.Drawers.init();
    Site.Share.init();
  },

  onResize: function() {
//     var _this = this;

  },

  fixWidows: function() {
    // utility class mainly for use on headines to avoid widows [single words on a new line]
    $('.js-fix-widows').each(function(){
      var string = $(this).html();
      string = string.replace(/ ([^ ]*)$/,'&nbsp;$1');
      $(this).html(string);
    });
  },

  bindPinScroll: function() {
    $('#footer-pin-holder').on('click', function() {
      $('html, body').animate({ scrollTop: 0 }, Site.animationSpeed);
    });
  }
};

Site.Shuffle = {
  init: function() {
    var _this = this;
    _this.shuffleInstances = [];

    if ($('.shuffle-section').length) {
      _this.bind();
    }

  },

  bind: function() {
    var _this = this;

    $('.shuffle-section').each(function(index, item) {
      var $container = $(item).children('.shuffle-container');

      if ($container.find('img').length) {
        imagesLoaded($container[0], function() {
          _this.initShuffle($container[0], $(item), index);
        });
      } else {
        _this.initShuffle($container[0], $(item), index);
      }
    });

  },

  initShuffle: function(container, $section, index) {
    var _this = this;

    $section.children('.shuffle-preloader').remove();
    $(container).removeClass('hidden');

    _this.shuffleInstances[index] = new Shuffle(container, {
      itemSelector: '.shuffle-item',
      sizer: '.shuffle-item',
      throttleTime: 200,
      staggerAmount: 10,
      staggerAmountMax: 150,
    });

    container.addEventListener('load', function() {
      _this.update(index);
    }, true);

  },

  update: function(index) {
    var _this = this;

    if (index !== undefined) {
      // if update is called with an index variable just update that shuffle instance
      if (_this.shuffleInstances[index]) {
        _this.shuffleInstances[index].update();
      }
    } else {
      // otherwise update all shuffle instances
      if (_this.shuffleInstances.length > 0) {
        for (var i = 0; i < _this.shuffleInstances.length; i++) {
          _this.shuffleInstances[i].update();
        }
      }
    }

  }
};

Site.Galleries = {
  init: function() {
    var _this = this;
    var $galleries = $('.swiper-container');

    if ($galleries.length) {
      $galleries.each(function(index, container) {
        if ($(this).hasClass('home-carousel')) {
          _this.initHomeCarousel(index, container);
        } else {
          _this.initGalleryInstance(index, container);
        }
      });
    }
  },

  initGalleryInstance: function(index, container) {
    var _this = this;

    _this['gallery-instance' + index] = new Swiper(container, Site.swiperVariables);
  },

  initHomeCarousel: function(index, container) {
    var _this = this;
    var $slides = $(container).find('.swiper-slide');

    $slides.each(function(index, item) {
      var background = $(item).data('background');

      $(item).css({
        'background-image': 'url(' + background + ')'
      });

    });

    _this['gallery-instance' + index] = new Swiper(container, {
      speed: 1250,
      autoplay: 3000,
      pagination: '.swiper-pagination',
      paginationType: 'bullets',
      paginationClickable: true,
      loop: true,
      paginationBulletRender: function(swiper, index, className) {
        return '<div class="carousel-pagination-item u-pointer ' + className + '"><span class="carousel-pagination-item-number">' + (index + 1) + '</span></div>';
      },
    });
  }
};

Site.Luminaries = {
  init: function() {
    if ($('body').hasClass('post-type-archive-luminaries')) {
      this.Archive.init();
    } else if ($('body').hasClass('single-luminaries')) {

    }
  }
};

Site.Luminaries.Archive = {
  init: function() {
    this.bind();
  },

  bind: function() {
    var _this = this;

    $('#luminaries-sort-alphabetical').on('click', function() {

      $('#posts-drawer').slideUp((Site.animationSpeed + 150), function() {
        $('#alphabetical-drawer').slideDown(Site.animationSpeed);
      });

      $(this).hide();
      $('#luminaries-sort-order').show();
    });

    $('#luminaries-sort-order').on('click', function() {

      $('#alphabetical-drawer').slideUp((Site.animationSpeed + 150), function() {
        $('#posts-drawer').slideDown(Site.animationSpeed);
      });

      $(this).hide();
      $('#luminaries-sort-alphabetical').show();
    });
  },

};

Site.Expandables = {
  init: function() {
    var _this = this;

    _this.bind();
  },

  bind: function() {
    $('.expandable-toggle').click( function() {

      $(this).toggle();

      var $expandableId = $(this).data('exapandable-id');

      // Toggle content
      $('#' + $expandableId).slideToggle(Site.animationSpeed, function() {
        if (Site.Shuffle.shuffleInstances) {
          Site.Shuffle.update();
        }
      });
    });
  },

};

Site.Drawers = {
  init: function() {
    var _this = this;

    _this.bind();

    _this.$validationError = $('.gform_validation_error');
    _this.$submitConfirmation = $('.gform_confirmation_message');

    if (_this.$validationError.length) {
      _this.openSubmitSection(_this.$validationError);
    }

    if (_this.$submitConfirmation.length) {
      _this.openSubmitSection(_this.$submitConfirmation);
    }

    if ($('body').hasClass('page-about')) {
      _this.About.init();
    }
  },

  bind: function() {
    $('.drawer-toggle').click( function() {
      var $drawer = $(this).data('drawer-id');

      // Toggle content
      $('#' + $drawer).slideToggle(Site.animationSpeed);
    });
  },

  openSubmitSection: function($target) {
    for (var x = 0; x < $target.length; x++) {
      $target.closest('.drawer-content').slideToggle(Site.animationSpeed, function() {
        $('html, body').animate({
          scrollTop: $(this).offset().top
        }, Site.animationSpeed);
      });
    }
  },
};

Site.Drawers.About = {
  init: function() {
    var _this = this;

    _this.bind();
  },

  bind: function() {
    var _this = this;

    $('.about-page-drawer-trigger').on('click', function() {
      _this.toggleDrawer(this);
    });
  },

  toggleDrawer: function(clickedTrigger) {
    var target = $(clickedTrigger).data('target');

    if ($(clickedTrigger).hasClass('active')) {
      return;
    }

    $('.about-page-drawer.active').slideUp((Site.animationSpeed + 150), function() {
      $('#about-drawer-' + target).slideDown(Site.animationSpeed);

      $('.about-page-drawer-trigger.active').removeClass('active');
      $(clickedTrigger).addClass('active');

      $('.about-page-drawer.active').removeClass('active');
      $('#about-drawer-' + target).addClass('active');

      if (target === 'people' && Site.Shuffle.shuffleInstances) {
        Site.Shuffle.update();
      }
    });
  }
};

Site.Media = {
  init: function() {
    var _this = this;

    _this.bind();
  },

  bind: function() {
    var _this = this;

    if ($('.media-items').length) {
      $('.media-item-image-holder').on({
        'click': function() {
          var $target = $(this).parents('.media-item');
          var targetHeight = $target.find('.media-item-image').height();

          _this.unloadActive();

          $target.addClass('active');

          _this.loadMedia($target, targetHeight);

          Site.Shuffle.update();

          clearTimeout(_this.scrollToTimeout);
          _this.scrollToTimeout = setTimeout(function() {
            zenscroll.to($target[0]);
          }, 200);
        }
      });

      $('.media-stop-button').on({
        'click': function() {
          var $target = $(this).parents('.media-item');

          _this.unloadActive();

          Site.Shuffle.update();

          clearTimeout(_this.scrollToTimeout);
          _this.scrollToTimeout = setTimeout(function() {
            zenscroll.to($target[0]);
          }, 200);
        }
      });

    }
  },

  unloadActive: function() {
    var _this = this;
    var $active = $('.media-item.active');

    if ($active.hasClass('playing-video')) {
      $('#media-item-video-embed').remove();
      $active.removeClass('playing-video');
    }

    if ($active.hasClass('playing-audio')) {
      $('#media-item-audio-embed').remove();
      $active.removeClass('playing-audio');
    }

    $active.removeClass('active');
  },

  loadMedia: function($item, targetHeight) {
    var _this = this;
    var data = $item.data();

    if (data.vimeo) {
      _this.loadVideo($item, data.vimeo);
    } else if (data.soundcloud) {
      _this.loadAudio($item, data.soundcloud, targetHeight);
    }
  },

  loadVideo: function($item, vimeoId) {
    var _this = this;
    var insert = '<div id="media-item-video-embed" class="u-video-embed-container"><iframe src="https://player.vimeo.com/video/' + vimeoId + '?title=0&byline=0&portrait=0&autoplay=1" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe></div>';

    $item.addClass('playing-video');
    $item.find('.media-item-image-holder').append(insert);
  },

  loadAudio: function($item, soundcloudUrl, targetHeight) {
    var _this = this;

    soundcloudUrl = _this.makeURL(soundcloudUrl);

    var insert = '<div id="media-item-audio-embed" style="height: ' + targetHeight + 'px;"><iframe width="100%" height="' + targetHeight + '" scrolling="no" frameborder="no" src="https://w.soundcloud.com/player/?url=' + soundcloudUrl + '&amp;auto_play=true&amp;hide_related=true&amp;show_comments=false&amp;show_user=false&amp;show_reposts=false&amp;visual=true"></iframe></div>';

    $item.addClass('playing-audio');
    $item.find('.media-item-image-holder').append(insert);
  },

  makeURL: function(url) {
    var urlParts = url.split('/');

    // Check if url comes with token
    if (urlParts.length === 6) {

      // Replace token in path as a query param
      url = url.replace(urlParts[5],'?secret_token=' + urlParts[5]);
    }

    return encodeURIComponent(url);
  },
};

Site.Lightbox = {
  init: function() {
    var _this = this;

    _this.$lightbox = $('#lightbox');
    _this.$lightboxTitle = $('#lightbox-title');
    _this.$lightboxContent = $('#lightbox-content');

    _this.bind();
  },

  bind: function() {
    var _this = this;

    $('[data-lightbox]').on({
      'click': function() {
        var data = $(this).data();

        _this.setTitle(data.lightboxTitle);

        if (data.lightbox === 'image') {
          _this.openImage(data);
        } else if (data.lightbox === 'gallery') {
          _this.openGallery(this);
        }

      }
    });

    _this.$lightbox.on({
      'click': function(e) {
        if (e.target.id === 'lightbox' || e.target.id === 'lightbox-close') {
          _this.hide();
        }
      }
    });
  },

  show: function() {
    var _this = this;

    $('body').addClass('lightbox-active');
    _this.$lightbox.css('display', 'flex');

  },

  showGallery: function(gallery) {
    var _this = this;

    _this.show();

    new Swiper(gallery, Site.swiperVariables);
  },

  hide: function() {
    var _this = this;

    _this.$lightbox.hide();
    $('body').removeClass('lightbox-active');
    _this.$lightboxTitle.html('');
    _this.$lightboxContent.html('');
  },

  setTitle: function(title) {
    var _this = this;

    _this.$lightboxTitle.html(title);
  },

  openImage: function(data) {
    var _this = this;
    var insert = '<img class="lightbox-image" src="' + data.lightboxImage + '" />';

    _this.$lightboxContent.append(insert);

    _this.show();
  },

  openGallery: function(element) {
    var _this = this;

    var $gallery = $(element).siblings('.gallery').clone();
    $gallery.addClass('swiper-container').removeClass('u-hidden');
    _this.$lightboxContent.append($gallery);

    _this.showGallery($gallery);
  },
};

Site.Share = {
  init: function() {
    var _this = this;

    if ($('.share-widget').length) {
      _this.bind();
    }
  },

  bind: function() {
    var _this = this;

    $('.share-trigger').on({
      'click': function() {
        var $target = $(this).parent();

        _this.toggle($target);
      }
    });
  },

  toggle: function($shareWidget) {
    var _this = this;

    if ($shareWidget.data('open')) {
      $shareWidget.find('.share-trigger').show();
      $shareWidget.find('.share-list').hide();
      $shareWidget.data('open', false);
    } else {
      $shareWidget.find('.share-trigger').hide();
      $shareWidget.find('.share-list').css('display', 'flex');

    }
  }
}

Site.init();
