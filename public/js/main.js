$(function () {
  $('.post_detail_menu').click(function () {
    $('#overlay').fadeIn();
    $('.modal_menu').fadeIn();
    $('.modal_menu').animate({bottom:0}, 300);
  });


  $('#cancel1, .overlay, #report_button').click(function () {
    $('#overlay').fadeOut();
    $('.modal_menu').animate({bottom: -350}, 300);
    $('.modal_menu').fadeOut();
  });
});


$(function () {
  $('#report_button').click(function () {
    $('#overlay').fadeIn();
    $('.report').fadeIn();
    $('.report').animate({bottom:0}, 400);
  });

  $('#cancel2, #overlay').click(function () {
    $('#overlay').fadeOut();
    $('.report').animate({bottom: -500}, 300);
    $('.report').fadeOut();
  });
});


//discovered modal
$(function () {
  $('.discovered_menu').click(function () {

    let modal = $(this).parent();

    $('#overlay').fadeIn();
    modal.find('.d_modal_menu').fadeIn();
    modal.find('.d_modal_menu').animate({bottom:0}, 200 );
  });


  $('.cancel, .overlay').click(function () {
    $('#overlay').fadeOut();
    $('.d_modal_menu').animate({bottom: -250}, 300);
    $('.d_modal_menu').fadeOut();
  });
});


//comment modal
$(function () {
  $('.comment_menu').click(function () {

    let modal = $(this).parent();

    $('#overlay').fadeIn();
    modal.find('.c_modal_menu').fadeIn();
    modal.find('.c_modal_menu').animate({bottom:0}, 200 );
  });


  $('.c_cancel1, #overlay, .c_report_button').click(function () {
    $('#overlay').fadeOut();
    $('.c_modal_menu').animate({bottom: -250}, 300);
    $('.c_modal_menu').fadeOut();
  });
});



$(function () {
  $('.c_report_button').click(function () {

    let modal = $(this).parent().parent().parent();
    console.log(modal);

    $('#overlay').fadeIn();
    modal.find('.c_report').fadeIn();
    modal.find('.c_report').animate({bottom:0}, 400);
  });

  $('.c_cancel2, #overlay').click(function () {
    $('#overlay').fadeOut();
    $('.c_report').animate({bottom: -500}, 300);
    $('.c_report').fadeOut();
  });
});
