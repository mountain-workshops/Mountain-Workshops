
(function ($) {
  $(document).ready(function() {
  
  
    // Widow killer from http://css-tricks.com/3563-preventing-widows-in-post-titles/
    $('.field-name-body p').each(function() {
      var wordArray = $(this).text().split(" ");
      wordArray[wordArray.length-2] += "&nbsp;" + wordArray[wordArray.length-1];
      wordArray.pop();
      jQuery(this).html(wordArray.join(" "));
    });
    
    $("div.view-faculty-staff div.views-field-title").hover(
  		function() {
          	$(this).siblings("div.views-field-field-faculty-staff-image").stop()
  			.animate({
              left: "20",
              opacity: 1
          },
          "fast")
          .show();
  
      },
      function() {
          $(this).siblings("div.views-field-field-faculty-staff-image").stop()
          .animate({
              left: "0",
              opacity: 0
          },
          "fast", 
          function(){
            $(this).hide()
          });
      }
    );

    
  });
}(jQuery));


