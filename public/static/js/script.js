var Prelaunch = (function(){ function Prelaunch($) { this._ = 'prelaunch'; var self = this; return $.extend(self, {
    
    init : function()
    {
        //console.log('init');
        $(self.docready);
    },
    
    docready : function()
    {
        //$.stellar();
        
        //$('.backslide').bind('inview',function(ev,is_in){
        //    //if(is_in) $(this).addClass('inview');
        //    //else $(this).removeClass('inview');
        //});
        
        //$('#content-back').scrollingParallax({
        //    staticSpeed: 0.7,
        //    staticScrollLimit: false,
        //});
        //$.scrollingParallax('/static/images/para_bg.jpg',{
        //    staticSpeed: 0.7,
        //    staticScrollLimit: false,
        //    bgHeight:'7210px',
        //});
        //$('#content-back').sinaptillax(1);
        //$('#content-back').parallax('50%',0.7);
        
        //$('.backwhitefix').click(function(){
        //    var page = parseInt( window.scrollY / 920 ) + 1;
        //    if (page > 10) return;
        //    $.scrollTo(page * 920 + 150, 1200);
        //});
        
        (function() {
            var $pimgs = $('.prod_img');
            $pimgs.each(function(i,e){
                $(e).data('iv_lock', false).data('orig_height', $(e).height());
                $(e).css({height:0});
                $(e).bind('inview', function(ev,is_in,pX,pY){
                    var $this = $(this);
                    if ($this.data('iv_lock')) return;
                    else {
                        $this.data('iv_lock', true);
                        if (is_in && ! $this.is(':visible') || parseInt($this.height()) == 0) {
                            $this.delay(600).animate({height:$this.data('orig_height')},{
                                easing:'swing',
                                duration: 600,
                                complete: function(){
                                    $(this).css({display:'block'}).data('iv_lock', false);
                                },
                            });
                        }
                    }
                });
            });
        })();
    }
    
    
}).init(); }; return new Prelaunch(jQuery); })();
