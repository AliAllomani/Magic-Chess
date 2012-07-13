/*
 * Settings
 */
 
/*  pieces update timer */
window.play_update_interval = 1000;

/*  active player update timer */
window.play_connection_update_interval = 3000;

/* lobby tables update timer */
window.lobby_update_interval = 3000;


/*
 * Update player alive time connection
 */
function update_connection(){
             
    $.post('ajax',{
        action: 'update_connection'
    },function(data){
            
        if(data == "0"){
            connection_lost();
        }
    });
               
}
 
/* 
 * Connection lost function
 */
function connection_lost(){
    clearInterval(window.update_connection_interval_handler);     
    alert('connection with other player lost !');
             
    window.location = 'lobby';
}
           

/*
 * Peices wight
 */
pieces = {
    "KING": 7 , 
    "ADVISER": 6 , 
    "BISHOPS": 5 , 
    "ROOKS": 4 , 
    "KNIGHTS": 3 , 
    "CANNONS": 2 , 
    "PAWNS": 1
};
          