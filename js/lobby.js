$(document).ready(function(){
    
    window.my_table = 0 ;
    $("#status").html('select a table ...');
 
    /*
     * Table Click Handler
     */
    $(".table").click(function(event){
        if(window.my_table == 0){
    
            window.my_table = $(this).attr('id');
            $("#status").html('please wait ...');
            
            /*
             * Send Ajax request for new player
             */
            $.post('ajax',{
                action: 'new_player',
                table: $(this).attr('id')
            },function(data){
                   
                  /*
                   * if new player accepted , put it on waiting .
                   */
                if(data == "1"){
            
                    $('#'+window.my_table).addClass('selected_table');
      
      
                    $(".table").unbind('click');
                    $("#status").html('waiting for other player ...');
       
                    check_tables();
                }else{
                    
                    /*
                     * if player rejected show a message for him and return it to select status
                     */
                    window.my_table = 0;
                    alert('you cannot enter this table');   
                    $("#status").html('select a table ...');
                    
                }
            });
        }else{
            alert('you already select a table');
        }
    });

 

    check_tables();
    
});

/**
*  Checking tables data
*/
function check_tables(){
   
    jQuery.getJSON('ajax',{
        action: 'check_tables'
    },function(data){
     
        $('.player').removeClass('player_black_on');
        $('.player').removeClass('player_red_on');
    
        $.each(data,function(key,val){
    
            if(val.redirect == "1"){
                window.location = 'play'; 
                return true;    
            }
    
   
            if(val.red_sid){
                $('#t'+val.id+'-red').addClass('player_red_on');  
            }
   
            if(val.black_sid){
                $('#t'+val.id+'-black').addClass('player_black_on');    
            }
    
    
        });
    
    
    });
   
    
    setTimeout(check_tables,window.lobby_update_interval); 
}