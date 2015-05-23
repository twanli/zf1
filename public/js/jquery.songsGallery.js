
(function($){
    
    $.songsGallery = function() {
          
        var plugin = this;
        
        plugin.sort = function() {
            debugger;
            var singerId = $('.reorder-photos').data('id');
            var start_pos = 0;
            var end_pos = 0;
            
            $('ul.gallery-imgs').sortable(
                { tolerance: 'pointer',
                  start: function(event, ui) {
                    var start_pos = ui.item.index() + 1;
                    
                    
                    
                    ui.item.data('start_pos', start_pos);
                  },
                  update: function(event, ui) {
                      //debugger;
                      var start_pos = ui.item.data('start_pos');
                      var end_pos = ui.item.index() + 1;
                      var moved_item_id = (ui.item).attr('id');
                      
                      /*var h = [];
                      $("ul.gallery-imgs li").each(function() {  h.push($(this).attr('id'));  });*/
                      
                      $.post(baseUrl+"/songs/reorder",
                            {songId: moved_item_id, start_pos: start_pos, 
                            end_pos: end_pos, 
                            singerId: singerId}, function(error){
                                $(".error").html(error);
                            });                     

                }  
                
                
            });
            
            $('.reorder-photos').html('Finish reordering');
            $('.reorder-photos').attr("id","finish-reorder");
            $('.img-link').attr("href","javascript:void(0);");
            $('.img-link').css("cursor","move");
            
            $("#finish-reorder").click(function( e ){
                e.preventDefault();
                
                $('ul.gallery-imgs').sortable('destroy');
                window.location.href = baseUrl+'/songs/index/singer/'+singerId;                
            });                
        }
        //var config = settings;
        

        plugin.init = function() {
            $('.fancybox').fancybox();
        }
        // Init plugin
        plugin.init();
 
        return this;
    };
    
})(jQuery);


