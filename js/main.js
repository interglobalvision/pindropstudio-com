/* jshint browser: true, devel: true, indent: 2, curly: true, eqeqeq: true, futurehostile: true, latedef: true, undef: true, unused: true */
/* global $, document, Site, Modernizr, shuffle */
var Shuffle = window.shuffle;

Site = {
  mobileThreshold: 601,
  init: function() {
    var _this = this;

    $(window).resize(function(){
      _this.onResize();
    });

    $(document).ready(function () {

    });

    Site.News.init();
    Site.Luminaries.init();
  },

  onResize: function() {
    var _this = this;

  },

  fixWidows: function() {
    // utility class mainly for use on headines to avoid widows [single words on a new line]
    $('.js-fix-widows').each(function(){
      var string = $(this).html();
      string = string.replace(/ ([^ ]*)$/,'&nbsp;$1');
      $(this).html(string);
    });
  },
};

Site.News = {
  init: function() {
    var _this = this;

    _this.shuffleContainer = document.getElementById('shuffle-container');

    if (_this.shuffleContainer) {
      _this.bind();
    }
  },

  bind: function() {
    var _this = this;

    imagesLoaded(_this.shuffleContainer, function() {
      _this.initShuffle();
    });
  },

  initShuffle: function() {
    var _this = this;

    $('#shuffle-preloader').remove();
    $(_this.shuffleContainer).removeClass('hidden');

    _this.shuffleInstance = new Shuffle(_this.shuffleContainer, {
      itemSelector: '.shuffle-item',
      sizer: '.shuffle-item',
      throttleTime: 200,
      staggerAmount: 10,
      staggerAmountMax: 150,
    });

    _this.shuffleContainer.addEventListener('load', function() {
      _this.shuffleInstance.update();
    }, true);
  },
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
      _this.sort('alphabetical');
      $(this).hide();
      $('#luminaries-sort-order').show();
    });

    $('#luminaries-sort-order').on('click', function() {
      _this.sort('order');
      $(this).hide();
      $('#luminaries-sort-alphabetical').show();
    });
  },

  sort: function(type) {
    var $posts = $('#posts');

    $posts.find('.type-luminaries').sort(function(a, b) {
      if (type === 'order') {

        return +b.getAttribute('data-sort-order') - +a.getAttribute('data-sort-order');

      } else {

        if (a.getAttribute('data-sort-alphabetical') == 'a'){
          return 0;
        }

        if ( b.getAttribute('data-sort-alphabetical') == 'a'){
          return 1;
        }

        return (a.getAttribute('data-sort-alphabetical') > b.getAttribute('data-sort-' + type) ? 1 : -1);

      }

    }).appendTo($posts);
  }
}

Site.init();