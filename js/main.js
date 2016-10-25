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

    _this.bind();
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
    $(_this.shuffleContainer).css('opacity', 1)

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

Site.init();