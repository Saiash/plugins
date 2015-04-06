var outercontainer = '.container'; //куда добавится превью
var imgclass = '.photo_preview'; //клик на картинку данного класса
var overlayclass = 'preview_overlay'; //внешний фон
var centerblock = 'preview_container_photo'; //внутренний контейнер
var left = 'preview_left';
var right = 'preview_right';
var close = 'preview_close';
$(document).ready(function(){

    $(imgclass).click(function(){
        var src = $(this).data('src');
        var name = $(this).data('name');
        if (name == undefined) {
            name = '';
        } else {
            name = '<h2>'+name+'</h2>';
        }
        var desc = $(this).data('desc');
        if (desc == undefined) {
            desc = '';
        }
        var index = $(this).index(imgclass);
        var count = $(this).parents('ul').find(imgclass).length;
        $(outercontainer).append('<div class="preview">' +
            '<div class="'+overlayclass+'"></div>' +
            '<div class="'+centerblock+'"><span  class="preview_name">'+name+'</span>'+
            '<div class="img_container" data-index="'+index+'" data-count=' +
            '"'+count+'"><div class="loading"></div></div>'+desc+
            '<div class="'+left+'"></div><div class="'+right+'"></div><div class="'+close+'">&times;</div></div>' +
            '</div>');
        var i = new Image();
        i.src = src;
        $(i).load(function (){
            $('.img_container').html(this);
        });

        $('.'+left).click(function(){
            var index = $('.img_container').data('index') - 1;
            var count = $('.img_container').data('count');
            if (index < 0) {
                index = count - 1;
            }
            var src = $(imgclass).eq(index).data('src');
            var name = $(imgclass).eq(index).data('name');
            if (name == undefined) {
                name = '';
            } else {
                name = '<h2 class="preview_name">'+name+'</h2>';
            }
            var desc = $(imgclass).eq(index).data('desc');
            if (desc == undefined) {
                desc = '';
            }
            i.src = src;
            $('.img_container').append('<div class="loading"></div>');
            $(i).load(function (){
                $('.preview_name').html(name);
                $('.preview_container_photo p').html(desc);
                $('.img_container').html(this).data('index', index);
            });
        });
        $('.'+right).click(function(){
            var index = $('.img_container').data('index') + 1;
            var count = $('.img_container').data('count');
            if (index >= count) {
                index = 0;
            }
            var src = $(imgclass).eq(index).data('src');
            var name = $(imgclass).eq(index).data('name');
            if (name == undefined) {
                name = '';
            } else {
                name = '<h2 class="preview_name">'+name+'</h2>';
            }
            var desc = $(imgclass).eq(index).data('desc');
            if (desc == undefined) {
                desc = '';
            }
            i.src = src;
            $('.img_container').append('<div class="loading"></div>');
            $(i).load(function (){
                $('.preview_name').html(name);
                $('.preview_container_photo p').html(desc);
                $('.img_container').html(this).data('index', index);
            });
        });
        $('.'+overlayclass).click(function() {
            $('.preview').remove();
            $('.'+overlayclass).remove();
        });
        $('.'+close).click(function() {
            $('.preview').remove();
            $('.'+overlayclass).remove();
        });

    });
});