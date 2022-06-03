$(function () {
  $('.stop').click(function () {
    $('#overlay').fadeIn();
  });

  $('.stop').click(function () {
    $('.user_stop').animate({bottom:0}, 400);
  });


  $('#cancel1, #overlay').click(function () {
    $('.user_stop').animate({bottom: -500}, 400);
  });

  $('#cancel1, #overlay').click(function () {
    $('#overlay').fadeOut();
  });

});

$(function () {
  $('.report_button').click(function () {
    $('#overlay').fadeIn();
  });

  $('.report_button').click(function () {
    $('.report').animate({bottom:0}, 600);
  });

  $('#cancel2, .overlay').click(function () {
    $('#overlay').fadeOut();
  });

  $('#cancel2, .overlay').click(function () {
    $('.report').animate({bottom: -500}, 400);
  });
});



$('#stopModal').on('show.bs.modal', function (event) {
  var button = $(event.relatedTarget); //モーダルを呼び出すときに使われたボタンを取得
  var userId = button.data('bs-stop_id'); //data-whatever の値を取得

  var modal = $(this) ; //モーダルを取得
  modal.find('.modal-title').text('ユーザーID [ ' + userId +' ]のアカウント停止期間を選択');
})
