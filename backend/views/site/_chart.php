  <?php
/*
 * Chua dung
 *
  Yii::$app->view->registerJs("
var chart = c3.generate({
    bindto: '$bindTo',
    data: {
        url: '$url',
        mimeType: 'json',
         type: 'pie'
    },
   
});

setTimeout(function () {
    c3.generate({
      bindto: '$bindTo',
      data: {
          url: '$url',
          mimeType: 'json',
          type: 'pie'
        },
    });
}, 1000);
");
  