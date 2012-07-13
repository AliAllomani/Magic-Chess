$(document).ready(function(){
           
    window.cur_player = 'black';
    window.cur_active = 0;
            
    //   $('#'+window.my_player+'_player').addClass('my_player');
    $('#my_player').html('You Are : ' + window.my_player.toUpperCase());
    $('#my_player').addClass('my_player_'+window.my_player);
    $(".place").click(piece_click);
    $("#pass").click(pass);
                 
                 
     /* end game handler */            
    $('#game_end').click(function(event){
        if(confirm('are you sure ?')){
            game_end();
        }
    });
            
    get_result();
            
});


/**
 * Piece Click Handler's function
 */            
function piece_click(){
    
    /*
     * Check if it's the player turn or not 
     */
    if(window.cur_player != window.my_player){
        show_error('it\'s not your turn');
        return false;
    }
  
   
    if(window.cur_active > 0){
       
        /*
        * if there is an active selection
        */
       
       /* if peice not flipped show error message */
        if($(this).hasClass('cover')){
            show_error('you can not move to none flipped piece');
            disactivate();
            return true;    
        }
        
        /* if the player click on selected piece again disactivate the selection */
        if(window.cur_active == $(this).attr('id')){
            disactivate();
            return true;
        }
    
        /* check if the target place is allowed in move scenario */
        window.cur_active = parseInt(window.cur_active);
        move_array =  [(window.cur_active - 8) , (window.cur_active + 8) , (window.cur_active - 1) , (window.cur_active +1)]
        if($.inArray(parseInt($(this).attr('id')),move_array) == -1){
            disactivate();
            show_error('move around the piece only');
            return false;
        }
        
        /* check if the target peice is owned by same player */
        if(window.cur_player == $(this).attr('player')){
            disactivate();
            show_error('You cant eat your own piece');
            return false;
        }
                    
        /* check if target peice is allowed to be captured or it's an empty place */           
        active_piece = $('#'+window.cur_active).attr('piece');
        target_piece = $(this).attr('piece');
                    
        if(pieces[active_piece] >= pieces[target_piece] || (pieces[active_piece]==1 && pieces[target_piece]==7) || $(this).attr('player') == "none"){
            $(this).attr('player',$('#'+window.cur_active).attr('player'));
            $(this).attr('piece',$('#'+window.cur_active).attr('piece'));
            $(this).css('background-image',$('#'+window.cur_active).css('background-image'));
            $('#'+window.cur_active).css('background-image','');
            $('#'+window.cur_active).attr('player','none');
            $('#'+window.cur_active).attr('piece','none');
    
            disactivate();
            change_player();    
        }else{
            disactivate();
            show_error(active_piece + ' cannot eat ' + target_piece);
        }
   
    }else{
           
        /*
          * if there is no active selection
          */
         
        /* check if peice is not flipped or not owned by player */
         
        if($(this).attr('player') == window.cur_player || $(this).hasClass('cover')){
                    
                        
            /* Flip the peice */
             
            if($(this).hasClass('cover')){
                $(this).removeClass('cover');
                $(this).css("background-image","url('img/" + $(this).attr('player') + "_" + $(this).attr('piece') + ".png')");
                        
                disactivate();
                change_player();
   
                return true;
            }
                    
            /* Activate piece selection */
                    
            $(".place").removeClass('active');
            $(this).addClass('active');
            window.cur_active = $(this).attr('id');                  
                            
        }else{
            show_error('You can not select this piece');
            return false;
        }
    }
    
    return true;
}
    
    
/**
 * change turn
 */
function change_player(){
    if(window.cur_player=='black'){
        window.cur_player='red';
    }else{
        window.cur_player='black';
    }
    //  $("#cur_player").html(window.cur_player + ' Turn !');
    //    get_result();
    set_data();
}

/*
 * Show Error message function
 */
function show_error(msg){
    $('#error').html(msg);
    $('#error').show();
    $('#error').fadeOut(2000);
}
  
/**
 * Send peices data to ajax handler file
 */
function set_data(){
    var data = {};
    $('.place').each(function(index){
        data[index] = {
            'id': $(this).attr('id') , 
            'player': $(this).attr('player') , 
            'piece': $(this).attr('piece'),
            'cover': $(this).hasClass('cover')
        } ;
    });
    $.post('ajax',{
        action: 'set' , 
        pieces: data , 
        cur_player: cur_player
    });
 
    return true;
}
    
    
/*
 * Get peices data from ajax handler file
 */
function get_data(){
                
    setTimeout(get_data,window.play_update_interval); 
    if(window.cur_player == window.my_player){
        return true;
    }
             
    $.getJSON('ajax',{
        action: 'get'
    },function(data){
     
                
               
        window.cur_player = data.cur_player;
        //       $("#cur_player").html(cur_player + ' Turn !');
                 
        $.each(data.pieces,function(i, item){
                
            $('#'+item.id).attr('player',item.player);  
            $('#'+item.id).attr('piece',item.piece); 
                       
                           
            if(item.cover == "true"){
                $('#'+item.id).css("background-image",'');
                $('#'+item.id).addClass('cover'); 
                             
            }else{
                $('#'+item.id).removeClass('cover');
                            
         
                if(item.player == 'none'){
                    $('#'+item.id).css("background-image",'');
                            
                }else{
                                
                            
                    $('#'+item.id).css("background-image","url('img/" + item.player + "_" + item.piece + ".png')"); 
                }
         
            }
        }); 
                
                    
        get_result();
               
    });
    
    return true;
}
function pass(){
    if(window.cur_player == window.my_player){
        change_player();
    }
}
  
/*
 * Game End Button's Click Handler
 */
function game_end(){
    $.post('ajax',{
        action: 'game_end'
    },function(data){
        window.location = 'lobby';    
        
    });  
    
    return true;
}
 
/*
*  Getting Current players result and check if someone win or lose
*  and handle pass button disable status
*/
function get_result(){
                
    if(window.my_player == window.cur_player){
                
        $('#pass').removeAttr("disabled"); 
    }else{
        $('#pass').attr('disabled','disabled');           
    }
                
    $("#cur_player").html(window.cur_player + ' Turn !');
    black_peices = $("div[player=black]").length;
    red_peices = $("div[player=red]").length;
                
    $("#result").html("black : " + black_peices + " , red: " + red_peices);
                
    if(black_peices == 0){
        if(window.my_player == 'black'){
            show_lose();
        }else{
            show_win(); 
        }
    }
                
                
    if(red_peices == 0){
        if(window.my_player == 'red'){
            show_lose();
        }else{
                        
            show_win();
        }
    }
                
                
}
 
/*
 * Show Player Win Message and end the game
 */
function show_win(){
    alert(' You Win !!');
    game_end();
}
   
   
/*
 * Show Player lose message and end the game
 */
function show_lose(){
    alert(' You Lose :(');
    game_end();
}
            
            
/*
 * Disactivate current piece select
 */
function disactivate(){
    window.cur_active = 0;
    $(".place").removeClass('active');
}
            
        
/*
 * Get peices data and set connection updater timer 
 */          
get_data();
window.update_connection_interval_handler = setInterval("update_connection()",window.play_connection_update_interval);
        