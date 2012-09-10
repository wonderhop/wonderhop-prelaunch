var Prelaunch = (function(){ function Prelaunch($) { this._ = 'prelaunch'; var self = this; return $.extend(self, {
    
    init : function()
    {
        console.log('init');
        $(self.docready);
    },
    
    docready : function()
    {
        //$.stellar();
        
        $('.backslide').bind('inview',function(ev,is_in){
            //if(is_in) $(this).addClass('inview');
            //else $(this).removeClass('inview');
        });
        
        $('#content-back').scrollingParallax({
            staticSpeed: .8,
            staticScrollLimit: false,
        });
        
        $('body').click(function(){
            var page = parseInt( window.scrollY / 920 ) + 1;
            if (page > 9) return;
            $.scrollTo(page * 920 + 150, 800);
        });
    }
    
    
}).init(); }; return new Prelaunch(jQuery); })();
