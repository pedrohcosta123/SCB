<html>
   <head>
       <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
       <title>Exemplo de upload</title>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<!-- Generic page styles -->
<link rel="stylesheet" href="css/style.css">
<!-- blueimp Gallery styles -->
<link rel="stylesheet" href="https://blueimp.github.io/Gallery/css/blueimp-gallery.min.css">
<!-- CSS to style the file input field as button and adjust the Bootstrap progress bars -->
<link rel="stylesheet" href="css/jquery.fileupload.css">
<link rel="stylesheet" href="css/jquery.fileupload-ui.css">
<!-- CSS adjustments for browsers with JavaScript disabled -->
<noscript><link rel="stylesheet" href="css/jquery.fileupload-noscript.css"></noscript>
<noscript><link rel="stylesheet" href="css/jquery.fileupload-ui-noscript.css"></noscript>
       <script type="text/x-tmpl">
       $(function () {
   $('#formulario').fileUploadUI({
       uploadTable: $('#files'),
       downloadTable: $('#files'),
       buildUploadRow: function (files, index) {
           return $(
           '<tr>'+
               '<td>' + files[index].name + '<\/td>' +
               '<td class="file_upload_progress">' +
                   '<div><\/div>' +
                '<\/td>' +
               '<td class="file_upload_start">' +
                   '<button class="ui-state-default ui-corner-all" title="Start Upload">' +
                       '<span class="ui-icon ui-icon-circle-arrow-e">Start Upload<\/span>' +
                   '<\/button>'+
               '<\/td>' +
                '<td class="file_upload_cancel">' +
                   '<button class="ui-state-default ui-corner-all" title="Cancel">' +
                       '<span class="ui-icon ui-icon-cancel">Cancel<\/span>' +
                   '<\/button>'+
               '<\/td>' +
           '<\/tr>');
       },
       buildDownloadRow: function (file) {
           return $('<tr><td>' + file.name + '<\/td><\/tr>');
       },
       beforeSend: function (event, files, index, xhr, handler, callBack) {
           var ext = /\.(png)|(jpg)|(gif)$/i;
           var num = 4;
           var maxsize = 500000;
           // Using the filename extension for our test,
           // as legacy browsers don't report the mime type
           if (index > num) {
               handler.uploadRow.find('.file_upload_progress').html('Selecione no máximo ' + num + ' arquivos');
           }
           if (!ext.test(files[index].name)) {
               handler.uploadRow.find('.file_upload_progress').html('Caro usuário, selecione somente imagens');
               setTimeout(function () {
                   handler.removeNode(handler.uploadRow);
               }, 10000);
               return;
           }
           if (files[index].size > maxsize) {
                   handler.uploadRow.find('.file_upload_progress').html('Caro usuário o arquivo '+ files[index].name +' contém '+ files[index].size/1000 +
                       'Kb, é maior do que: ' + maxsize/1000 + ' Kb. Selecione um arquivo menor');
                   setTimeout(function () {
                       handler.removeNode(handler.uploadRow);
                   }, 10000);
                   return;
           }
           handler.uploadRow.find('.file_upload_start button').click(callBack);
       }
   });

   $('#enviar').click(function () {
       $('.file_upload_start button').click();
   });

   $('#submeter').hide();
   $('#enviar').show();
});
       </script>
   </head>
   <body>

       <form id="formulario" action="upload.php" method="post" enctype="multipart/form-data">
           <input type="text" name="example_text">
           <div class="file_upload">
               <input type="file" name="file" multiple>
               <button>Upload</button>
               <div>Selecione os arquivos</div>
           </div>
           <input type="submit" id="submeter" value="enviar"/>
       </form>
       <input type="button" id="enviar" value="Enviar"/>
       <table id="files"></table>




<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<!-- The jQuery UI widget factory, can be omitted if jQuery UI is already included -->
<script src="js/vendor/jquery.ui.widget.js"></script>
<!-- The Templates plugin is included to render the upload/download listings -->
<script src="https://blueimp.github.io/JavaScript-Templates/js/tmpl.min.js"></script>
<!-- The Load Image plugin is included for the preview images and image resizing functionality -->
<script src="https://blueimp.github.io/JavaScript-Load-Image/js/load-image.all.min.js"></script>
<!-- The Canvas to Blob plugin is included for image resizing functionality -->
<script src="https://blueimp.github.io/JavaScript-Canvas-to-Blob/js/canvas-to-blob.min.js"></script>
<!-- Bootstrap JS is not required, but included for the responsive demo navigation -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<!-- blueimp Gallery script -->
<script src="https://blueimp.github.io/Gallery/js/jquery.blueimp-gallery.min.js"></script>
<!-- The Iframe Transport is required for browsers without support for XHR file uploads -->
<script src="js/jquery.iframe-transport.js"></script>
<!-- The basic File Upload plugin -->
<script src="js/jquery.fileupload.js"></script>
<!-- The File Upload processing plugin -->
<script src="js/jquery.fileupload-process.js"></script>
<!-- The File Upload image preview & resize plugin -->
<script src="js/jquery.fileupload-image.js"></script>
<!-- The File Upload audio preview plugin -->
<script src="js/jquery.fileupload-audio.js"></script>
<!-- The File Upload video preview plugin -->
<script src="js/jquery.fileupload-video.js"></script>
<!-- The File Upload validation plugin -->
<script src="js/jquery.fileupload-validate.js"></script>
<!-- The File Upload user interface plugin -->
<script src="js/jquery.fileupload-ui.js"></script>
<!-- The main application script -->
<script src="js/main.js"></script>
   </body>
</html>
