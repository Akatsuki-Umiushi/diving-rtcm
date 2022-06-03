// var exampleModal = document.getElementById('exampleModal')
// exampleModal.addEventListener('show.bs.modal', function (event) {
//   // Button that triggered the modal
//   var button = event.relatedTarget
//   // Extract info from data-bs-* attributes
//   var recipient = button.getAttribute('data-bs-whatever')
//   // If necessary, you could initiate an AJAX request here
//   // and then do the updating in a callback.
//   //
//   // Update the modal's content.
//   var modalTitle = exampleModal.querySelector('.modal-title')
//   var modalBodyInput = exampleModal.querySelector('.modal-body input')
//
//   modalTitle.textContent = 'New message to ' + recipient
//   modalBodyInput.value = recipient
// })
// $('#exampleModal').on('show.bs.modal', function (event) {
//   var button = $(event.relatedTarget) //モーダルを呼び出すときに使われたボタンを取得
//   var recipient = button.data('whatever') //data-whatever の値を取得
//
//   //Ajaxの処理はここに
//
//   var modal = $(this)  //モーダルを取得
//   modal.find('.modal-title').text('New message to ' + recipient) //モーダルのタイトルに値を表示
//   modal.find('.modal-body input#recipient-name').val(recipient) //inputタグにも表示
// })
//
$('#myModal').on('show.bs.modal', function (event) {
    // ボタンを取得
    var button = $(event.relatedTarget);
    // data-***の部分を取得
    var sampledata = button.data('bs-sample');
    var modal = $(this);
    // モーダルに取得したパラメータを表示
    // 以下ではh5のモーダルタイトルのクラス名を取得している
    modal.find('.modal-title').val(sampledata);
  })
