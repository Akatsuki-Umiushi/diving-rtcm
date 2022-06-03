// $(function(){
//   const imgs = ['./img/icon/heart_white.png', './img/icon/heart_black.png'];  // 画像ファイル名
//   let index = 0;  // インデックス番号
//
//   // 初期画像の表示
//   $('.good_img').attr('src', imgs[index]);
//
//   // ボタンクリックイベント
//   $('.good').click(function(){
//     // 最後の画像判定
//     if(index == imgs.length - 1){
//       index = 0;
//     }else{
//       index++;
//     }
//     // 画像の切り替え
//     $('.good_img').attr('src',  imgs[index]);
//   });
// });

$(function(){
    let $good = $('.good'), //いいねボタンセレクタ
                goodCreatureId; //投稿ID

    const imgs = ['./img/icon/heart_white.png', './img/icon/heart_black.png'];  // 画像ファイル名
    let index = $good.data('bs-good_index');  // インデックス番号
    let goodCount = $('.g_count').text();  // いいねカウント
    Number(goodCount);
    // 初期画像の表示
    $('.good_img').attr('src', imgs[index]);


    $good.on('click',function(e){
        e.stopPropagation();
        let $this = $(this);
        //カスタム属性（creature_id）に格納された投稿ID取得
        goodCreatureId = $good.data('bs-creature_id');
        $.ajax({
            type: 'POST',
            url: 'ajax.php', //post送信を受けとるphpファイル'
            data: {action: "actionGood", creatureId: goodCreatureId} //{キー:投稿ID}
        }).done(function(data){

              // 最後の画像判定
              if(index == imgs.length - 1){
                index = 0;
              }else{
                index++;
              }
              // 画像の切り替え
              $('.good_img').attr('src',  imgs[index]);


          // いいねの総数を表示

              //
              if(index == 1){
                goodCount++;
                $('.g_count').text(goodCount);
              }else if (index == 0) {
                goodCount--;
                $('.g_count').text(goodCount);
              }

        }).fail(function(msg) {
            console.log('Ajax Error');
        });
    });
});
