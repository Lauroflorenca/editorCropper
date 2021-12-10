<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Cropper.js</title>
  <link rel="stylesheet" href="cropper.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/css/dropify.css">
  <style>
    .container {
      margin: 20px auto;
      max-width: 640px;
    }

    img {
      max-width: 100%;
    }

    .preview {
  			overflow: hidden;
  			width: 160px; 
  			height: 160px;
  			margin: 10px;
  			border: 1px solid red;
		}
  </style>
</head>
<body>

    <div class="container">
        <div id="dropifyUpload">
            <h1>Upload</h1>
            <input type="file" class="dropify" id="upImg">
        </div>
    </div>
        
    <br>
    
    <div class="container" id="main">
        <button id="censurar">Censurar</button>
        <img id="image" src="">  
        <div class="preview"></div> 
    </div>


    <div id="resultado">  
        <img id="result" src="">
    </div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/js/dropify.min.js"></script>
<script src="cropper.js"></script>
<script>

    var editor = false;
    var image = document.getElementById('image');
    var result = document.getElementById('result');

    $(document).ready(function () {

        $('.dropify').dropify();

        $("#censurar").click(function(){
            var croppedCanvas;
            var maskedCanvas;

            if (!editor) {
                return;
            }

            // Crop
            croppedCanvas = editor.getCroppedCanvas();

            // Mask
            maskedCanvas = getMaskedCanvas(croppedCanvas, image, editor);

            // Show
            result.src = maskedCanvas.toDataURL();
        });

        
        $('#upImg').change(function(event){
            var files = event.target.files;
            console.log(event.target.files)

            var done = function(url){
                $('#image').attr("src", url);
            };

            if(files && files.length > 0)
            {
                reader = new FileReader();
                reader.onload = function(event)
                {
                    done(reader.result);
                    if(editor){ editor.destroy(); }
 
                    event.currentTarget.result
                    editor = new Cropper(image, {
                        preview: '.preview',
                        viewMode: 0,
                        guides: true,
                        center: true,
                        highlight: true,
                        cropBoxMovable: true,
                        cropBoxResizable: true,
                        ready: function () {
                        croppable = true;
                        },
                    });
                 
                };
                reader.readAsDataURL(files[0]);
            }

        });
    });


    
    function getMaskedCanvas(sourceCanvas, sourceImage, cropper) {
        var canvas = document.createElement('canvas');
        var context = canvas.getContext('2d');

        var maskWidth = cropper.getData().width;
        var maskHeight = cropper.getData().height;
        var maskTop =  cropper.getData().y;
        var maskLeft =  cropper.getData().x;

        var imageWidth = cropper.getImageData().naturalWidth;
        var imageHeight = cropper.getImageData().naturalHeight;
        var imageLeft = cropper.getImageData().left;
        var imageTop = cropper.getImageData().top;
        var imageAspect = cropper.getImageData().aspectRatio;

        canvas.width = imageWidth;
        canvas.height = imageHeight;

        context.imageSmoothingEnabled = true;
        context.drawImage(image, 0, 0, imageWidth, imageHeight);

        context.filter = "blur(5px)"
        context.drawImage(image, maskLeft, maskTop, maskWidth, maskHeight, maskLeft, maskTop, maskWidth, maskHeight);

        return canvas;
    }

</script>


</body></html>