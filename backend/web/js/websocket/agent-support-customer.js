var stompClient = null;
var subscription_id = null;
var uID = null;
var token = null;
var sessionChatId_now = null;
var departmentId = null;
var messageOrder = null;
var statusTicketNow = "WAITING";

var countWaiting = 0;
var countAssigned = 0;
var countOpen = 0;
var countPending = 0;
var countApprove = 0;

var chunks = []; //will be used later to record audio
var mediaRecorder = null; //will be used later to record audio
var audioBlob = null; //the blob that will hold the recorded audio
var inputType = "text";

var countStatusNow = 0;
var countLoadmoreSc = 0;

var oldTime = null;

var getedWaiting = false;
var getedAssigned = false;
var getedOpen = false;
var getedPending = false;
var getedApprove = false;

var setIntervalId = null;
var timeRecord = 0;

var agentClickAsssign = false;

var isRemoveForSearch = false;

var recordAudio = false;
var listFileUpload = null;
function connect() {

    var socket = new SockJS('https://cmscamid.ringme.vn:8443/helpdesk-camid-service/helpdesk/event');
    stompClient = Stomp.over(socket);
    stompClient.debug = () => {};
    stompClient.connect({"user": uID, "X-Authorization": token}, function (frame) {
        $('.user-index').show();
        $('#loader').hide();        

        subscription_id = stompClient.subscribe('/user/watching', function (frame) {

            const msg = JSON.parse(JSON.parse(frame.body));

            // lấy danh sách session chat to left menu
            if(msg.desc != null && msg.desc === "getListSc"){
                const listCm = msg.listCM;

                if(listCm.length === 0){
                    countLoadmoreSc = countStatusNow;
                }


                if(msg.type === "WAITING"){
                    listCm.forEach(function(element){
                        appendTicketToAnotherStatus(element.chatMessage , "home" , 
                        "WAITING" , element.clientId , true , null);
                    });
                }else if(msg.type === "ASSIGNED"){
                    
                    listCm.forEach(function(element){
                        appendTicketToAnotherStatus(element.chatMessage , "menu1" , 
                        "ASSIGNED" , element.clientId, true , null);
                    });
                }
                else if(msg.type === "OPEN"){
                    listCm.forEach(function(element){
                        appendTicketToAnotherStatus(element.chatMessage , "menu2" , 
                        "OPEN" , element.clientId, true , element.departmentHelp);
                    });
                }
                else if(msg.type === "PENDING"){
                    listCm.forEach(function(element){
                        appendTicketToAnotherStatus(element.chatMessage , "menu3" , 
                        "PENDING" , element.clientId, true , null);
                    });
                }
                else if(msg.type === "APPROVE"){
                    listCm.forEach(function(element){
                        appendTicketToAnotherStatus(element.chatMessage , "menu4" , 
                        "APPROVE" , element.clientId, true , element.rateLevel);
                    });
                }
                
            }
            else if(msg.type != null && msg.type === "getListCm"){
                // lấy danh sách message cho màn hình chat
                const listCm = msg.listCm;
                listCm.forEach(function(element){
                    if(messageOrder == null){
                        messageOrder = element.msgOrder;
                    }else if(element.msgOrder < messageOrder){
                        messageOrder = element.msgOrder;
                    }
                    appendChatMessageToTicket(element , false);
                });
            }
            else if(msg.desc != null && msg.desc === "countSc"){
                // đếm count các tab
                const countSc = msg.countSc;

                countWaiting = countSc.numWaiting;
                countAssigned = countSc.numAssigned;
                countOpen = countSc.numOpen;
                countPending = countSc.numPending;
                countApprove = countSc.numApprove;

                $("#countWaiting").text("(" + countWaiting + ")");
                $("#countAssigned").text("(" + countAssigned + ")");
                $("#countOpen").text("(" + countOpen + ")");
                $("#countPending").text("(" + countPending + ")");
                $("#countApprove").text("(" + countApprove + ")");
            }
            else if(msg.type != null && msg.desc === "searchListSc"){
                const listCm = msg.listCM;
                if(listCm.length === 0){
                    countLoadmoreSc = countStatusNow;
                    
                } 
                if(msg.type === "WAITING"){
                    if(isRemoveForSearch == false){
                        $("#home .list-group-item").remove();
                        isRemoveForSearch = true;
                    }
                    
                    listCm.forEach(function(element){
                        appendTicketToAnotherStatus(element.chatMessage , "home" , 
                        "WAITING" , element.clientId , true , null);
                    });
                }else if(msg.type === "ASSIGNED"){
                    if(isRemoveForSearch == false){
                        $("#menu1 .list-group-item").remove();
                        isRemoveForSearch = true;
                    }
                    listCm.forEach(function(element){
                        appendTicketToAnotherStatus(element.chatMessage , "menu1" , 
                        "ASSIGNED" , element.clientId, true , null);
                    });
                }
                else if(msg.type === "OPEN"){
                    if(isRemoveForSearch == false){
                        $("#menu2 .list-group-item").remove();
                        isRemoveForSearch = true;
                    }
                    listCm.forEach(function(element){
                        appendTicketToAnotherStatus(element.chatMessage , "menu2" , 
                        "OPEN" , element.clientId, true , element.departmentHelp);
                    });
                }
                else if(msg.type === "PENDING"){
                    if(isRemoveForSearch == false){
                        $("#menu3 .list-group-item").remove();
                        isRemoveForSearch = true;
                    }
                    listCm.forEach(function(element){
                        appendTicketToAnotherStatus(element.chatMessage , "menu3" , 
                        "PENDING" , element.clientId, true , null);
                    });
                }
                else if(msg.type === "APPROVE"){
                    if(isRemoveForSearch == false){
                        $("#menu4 .list-group-item").remove();
                        isRemoveForSearch = true;
                    }
                    listCm.forEach(function(element){    

                        appendTicketToAnotherStatus(element.chatMessage , "menu4" , 
                        "APPROVE" , element.clientId, true , element.rateLevel);
                    });
                }
                
            }
            else if(msg.type === "seenMessage"){
                
                if($("#" + statusTicketNow + "-" + msg.sessionChatId+ " .message")){
                    $("#" + statusTicketNow + "-" + msg.sessionChatId + " .message").css("font-weight", "normal");
                }
                
            }
        });

        stompClient.send("/helpdesk/session-chat/count", {},
                JSON.stringify({
                'agentId': uID
                }));

        // ******
        const url = new URL(window.location);
        let statusUrl = url.searchParams.get("status");
        
        if(statusUrl === "WAITING" || statusUrl == null){

            $("#sc-tab1-1").click();
        }
        else if(statusUrl === "ASSIGNED"){
            
            $("#sc-tab2-1").click();
        }
        else if(statusUrl === "OPEN"){
            
            $("#sc-tab3-1").click();
        }
        else if(statusUrl === "PENDING"){
            
            $("#sc-tab4-1").click();
        }
        else if(statusUrl === "APPROVE"){
            
            $("#sc-tab5-1").click();
        }   

        let session_chat_id = url.searchParams.get("session_chat_id");
        if(session_chat_id != null){
            setTimeout(function(){
                        
                if($("#" + statusUrl + "-" + session_chat_id)){
                    
                    $("#" + statusUrl + "-" + session_chat_id).children('div').click();
                }
                else {
                    
                    url.searchParams.delete("session_chat_id");
                    window.history.pushState({}, '', url);
                }
            }, 500); 
            
        }

        subscription_id = stompClient.subscribe('/watching/waiting', function (frame) {
            const msg = JSON.parse(JSON.parse(frame.body));

             if(msg.type === "assign"){
                 // assign thành công xóa session chat trên left menu
                if(msg.status === "success"){
                    countWaiting = countWaiting - 1;
                    document.getElementById("WAITING-" + msg.sessionChatId).remove();
                }
                
            } else {
                // append new session chat to waiting tab
                if(!document.getElementById(msg.status + '-' + msg.id)){
                    countWaiting = countWaiting + 1;
                }
                appendTicketToAnotherStatus(msg.chatMessage , "home" , "WAITING" , 
                msg.clientId , false , null);
                if(msg.id === sessionChatId_now){
                    appendChatMessageToTicket(msg.chatMessage , true);
                }
                
            }
            $("#countWaiting").text("(" + countWaiting + ")");
        });

        subscription_id = stompClient.subscribe('/watching/assigned/' + uID, function (frame) {
            const msg = JSON.parse(JSON.parse(frame.body));

            if(msg.type === "assign"){
                // assign thành công append new session chat
                if(msg.status === "success"){

                countAssigned = countAssigned + 1;
                $("#countAssigned").text("(" + countAssigned + ")");

                checkAndAddNewTicketToStatusTab('ASSIGNED' , msg.lastMsg , 
                        msg.lastMsg.fromUserId , "menu1" , null);

                // click to ticket
                if(agentClickAsssign == true){
                    if(statusTicketNow != 'ASSIGNED'){
                        $("#sc-tab2-1").click();
                    }
                
                    setTimeout(function(){
                        
                        $("#" + statusTicketNow + "-" + msg.sessionChatId).children('div').click();
                    }, 400); 

                    agentClickAsssign = false;
                }
                    
                }else {
                    window.alert("assign failed");
                }
            }
            else if(msg.type === "close"){
                // close session chat 
                if(msg.status === "success"){
                    
                    document.getElementById('id01').style.display = 'none';
                    document.getElementById("chat-tab-id").style.display = 'none';

                    checkAndRemoveTicketInStatusTab("ASSIGNED" , msg.sessionChat.id);

                    countAssigned = countAssigned - 1;
                    $("#countAssigned").text("(" + countAssigned + ")");

                    sessionChatStatus = msg.sessionChat.status;

                    if(sessionChatStatus === "OPEN"){
                        countOpen = countOpen + 1;
                        $("#countOpen").text("(" + countOpen + ")");

                        checkAndAddNewTicketToStatusTab(sessionChatStatus , msg.sessionChat.chatMessage , 
                            msg.sessionChat.clientId , "menu2" , msg.sessionChat.departmentHelp);
                    }else if(sessionChatStatus === "PENDING"){
                        countPending = countPending + 1;
                        $("#countPending").text("(" + countPending + ")");

                        checkAndAddNewTicketToStatusTab(sessionChatStatus , msg.sessionChat.chatMessage , 
                            msg.sessionChat.clientId , "menu3" , null);
                    }else if(sessionChatStatus === "APPROVE"){
                        countApprove = countApprove + 1;
                        $("#countApprove").text("(" + countApprove + ")");

                        checkAndAddNewTicketToStatusTab(sessionChatStatus , msg.sessionChat.chatMessage , 
                            msg.sessionChat.clientId , "menu4" , msg.sessionChat.rateLevel);
                    }
                }
            }
            else if(msg.type === "reopen"){
                if(msg.status === "success"){
                    countOpen = countOpen - 1;
                    $("#countOpen").text("(" + countOpen + ")");

                    countAssigned = countAssigned + 1;
                    $("#countAssigned").text("(" + countAssigned + ")");
                    
                    checkAndRemoveTicketInStatusTab("OPEN" , msg.sessionChat.id);

                    checkAndAddNewTicketToStatusTab(msg.sessionChat.status , msg.sessionChat.chatMessage , 
                    msg.sessionChat.clientId , "menu1" , null);

                }
            }
            else if(msg.type === "supervisorCloseSc"){
                countPending = countPending - 1;
                $("#countPending").text("(" + countPending + ")");

                checkAndRemoveTicketInStatusTab("PENDING" , msg.sessionChat.id);

                if(msg.status === 'ASSIGNED'){
                    countAssigned = countAssigned + 1;
                    $("#countAssigned").text("(" + countAssigned + ")");

                    checkAndAddNewTicketToStatusTab(msg.status , msg.sessionChat.chatMessage , 
                        msg.sessionChat.clientId , "menu1" , null);
                }
                else if(msg.status === 'APPROVE'){
                    countApprove = countApprove + 1;
                    $("#countApprove").text("(" + countApprove + ")");

                    checkAndAddNewTicketToStatusTab(msg.status , msg.sessionChat.chatMessage , 
                        msg.sessionChat.clientId , "menu4" , msg.sessionChat.rateLevel);
                }
            }
            else {
                
                // append tin nhắn mới
                if(msg.status === "ASSIGNED"){    
                    appendTicketToAnotherStatus(msg.chatMessage , "menu1" , "ASSIGNED", 
                    msg.clientId , false , null);
                } 
                else if(msg.status === "OPEN"){    
                    
                    appendTicketToAnotherStatus(msg.chatMessage , "menu2" , "OPEN", 
                    msg.clientId , false , null);
                }
                else if(msg.status === "PENDING"){    
                    appendTicketToAnotherStatus(msg.chatMessage , "menu3" , "PENDING", 
                    msg.clientId , false , null);
                } 
                else if(msg.status === "APPROVE"){
                    
                    countApprove = countApprove + 1;
                    $("#countApprove").text("(" + countApprove + ")");

                    countAssigned = countAssigned - 1;
                    $("#countAssigned").text("(" + countAssigned + ")");

                    checkAndRemoveTicketInStatusTab("ASSIGNED" , msg.id);

                    appendTicketToAnotherStatus(msg.chatMessage , "menu4" , msg.status, 
                    msg.clientId , false , null);
                }
         
                if(msg.id === sessionChatId_now){
                    appendChatMessageToTicket(msg.chatMessage , true);
                }
            }
        });
    });
}

function checkAndAddNewTicketToStatusTab(status , chatMessage , clientId , location , moreInfo){

    if(status === statusTicketNow){
        
        appendTicketToAnotherStatus(chatMessage , location , statusTicketNow, 
        clientId , false , moreInfo);
    }
    else {
        if(status === "WAITING"){
            if(getedWaiting == true){
                appendTicketToAnotherStatus(chatMessage , location , status, 
                    clientId , false , moreInfo);
            }
        }else if(status === "ASSIGNED"){
            if(getedAssigned == true){
                appendTicketToAnotherStatus(chatMessage , location , status, 
                    clientId , false , moreInfo);
            }
        }
        else if(status === "OPEN"){
            if(getedOpen == true){
                appendTicketToAnotherStatus(chatMessage , location , status, 
                    clientId , false , moreInfo);
            }
        }
        else if(status === "PENDING"){
            if(getedPending == true){
                appendTicketToAnotherStatus(chatMessage , location , status, 
                    clientId , false , moreInfo);
            }
        }
        else if(status === "APPROVE"){
            if(getedApprove == true){
                appendTicketToAnotherStatus(chatMessage , location , status, 
                    clientId , false , moreInfo);
            }
        }    
    }

}

function checkAndRemoveTicketInStatusTab(status , sessionChatId){

    if(sessionChatId_now === sessionChatId){
        $("#chat-tab-id").hide();
        sessionChatId_now = null;
    }

    if(status === statusTicketNow){
        if($("#" + status + "-" + sessionChatId)){
            $("#" + status + "-" + sessionChatId).remove();
        }
    }
    else {
        if(status === "WAITING"){
            if(getedWaiting == true){
                if($("#" + status + "-" + sessionChatId)){
                    $("#" + status + "-" + sessionChatId).remove();
                }
            }
        }else if(status === "ASSIGNED"){
            if(getedAssigned == true){
                if($("#" + status + "-" + sessionChatId)){
                    $("#" + status + "-" + sessionChatId).remove();
                }
            }
        }
        else if(status === "OPEN"){
            if(getedOpen == true){
                if($("#" + status + "-" + sessionChatId)){
                    $("#" + status + "-" + sessionChatId).remove();
                }
            }
        }
        else if(status === "PENDING"){
            if(getedPending == true){
                if($("#" + status + "-" + sessionChatId)){
                    $("#" + status + "-" + sessionChatId).remove();
                }
            }
        }
        else if(status === "APPROVE"){
            if(getedApprove == true){
                if($("#" + status + "-" + sessionChatId)){
                    $("#" + status + "-" + sessionChatId).remove();
                }
            }
        }    
    }
}
// lấy danh sách session chat theo status
function getListSessionChatByStatus(status){
    
    
    removeFileData();
    removeVoiceData();
    $('#input-text').val("");
    let searchTerm;

    // ẩn ticket đang mở
    let div = document.getElementById(statusTicketNow + "-" + sessionChatId_now);
    if(div){
        div.style.backgroundColor='white';
        
    }
    
    document.getElementById("chat-tab-id").style.display = 'none';
    sessionChatId_now = null;

    var strStatus;

    let isExit = false;

    if(status == 1 ){
        searchTerm = $("#home .search-sessionchat").val();
        strStatus = "WAITING";
        countStatusNow = countWaiting;
        
        if(getedWaiting){
            isExit = true;
        } else {
            
            getedWaiting = true;
        }
    }
    else if(status == 2 ){
        searchTerm = $("#menu1 .search-sessionchat").val();

        strStatus = "ASSIGNED";
        countStatusNow = countAssigned;

        if(getedAssigned){
            isExit = true;
        } else {
            
            getedAssigned = true;
        }
    }
    else if(status == 3 ){
        searchTerm = $("#menu2 .search-sessionchat").val();

        strStatus = "OPEN";
        countStatusNow = countOpen;

        if(getedOpen){
            isExit = true;
        } else {
            
            getedOpen = true;
        }
    }
    else if(status == 4){
        searchTerm = $("#menu3 .search-sessionchat").val();

        strStatus = "PENDING";
        countStatusNow = countPending;

        if(getedPending){
            isExit = true;
        } else {
            
            getedPending = true;
        }
    }
    else if(status ==5){
        searchTerm = $("#menu4 .search-sessionchat").val();

        strStatus = "APPROVE";
        countStatusNow = countApprove;

        if(getedApprove){
            isExit = true;
        } else {
            
            getedApprove = true;
        }
    }
    
    countLoadmoreSc = 0;

    statusTicketNow = strStatus;

    // ******
    const url = new URL(window.location);
    url.searchParams.set('status' , strStatus);
    url.searchParams.delete("session_chat_id");
    window.history.pushState({}, '', url);

    // set scroll list ticket to top
    setTimeout(function(){
        
        $('#tab-content-custom').scrollTop(0);
    }, 300); 
    
    if(isExit){
        return;
    }
    
    
    isRemoveForSearch = false;
    
    
    stompClient.send("/helpdesk/session-chat/get",{},
        JSON.stringify({
            'agentId':uID,
            'status':strStatus,
            'limit':20,
            "lastScId":null,
            "termSearch": searchTerm != "" ? searchTerm : null
    }));

}


// thêm ticket to another status
function  appendTicketToAnotherStatus(msg, location , status , clientId , isOld , moreInfo){
    
    
    if($("#" + status + "-" + msg.sessionChatId)){
        $("#" + status + "-" + msg.sessionChatId).remove();
    }

    countLoadmoreSc++;
    let message = "";

    if(msg.typeMessage == "report"){
        message = "<span style=\"color:#a84032;\">Report from client</span>";
    }
    else if(msg.typeMessage != "text"){
        
        let typeFile = " Send a file";

        if(msg.typeMessage === "image"){
            typeFile = " Sent a image";
        }else if(msg.typeMessage === "file_2"){
            typeFile = " Sent a file";
        }else if(msg.typeMessage === "voicemail"){
            typeFile = " Sent a voice message";
        }else if(msg.typeMessage === "sharevideov2"){
            typeFile = " Send a video";
        }
        message = "<span class=\"glyphicon glyphicon-file\"></span>" + typeFile;
    }
    else {

        if(msg.fromType === "DEPARTMENT"){
            message = "Reply from department help : ";
        }

        message = message + escapeHtml(msg.message.slice(0 , 60));
        if(msg.message.length > 60){
            message = message + "...";
        }
        
    }

    // custom time format
    let timeCustom = "";
    let startOfDate = new Date();
    startOfDate.setUTCHours(0,0,0,0);

    let tmStartOfDate = startOfDate.getTime();
    if(msg.createdAt < tmStartOfDate){
        timeCustom = (new Date(msg.createdAt).toLocaleDateString("en-UK")) + " ";
    }

    timeCustom = timeCustom + timeConvert(msg.createdAt);
    
    // ----------------------------------------------------------------------------------------------
    let seen = (msg.seen == false && (status === "ASSIGNED" || status === "OPEN"));

    
    // neu đang mở thì seen luôn
    if((statusTicketNow === "ASSIGNED" || statusTicketNow === "OPEN") && msg.sessionChatId === sessionChatId_now && msg.seen == false){
        // seen = false;

        stompClient.send("/helpdesk/chat-message/seen", {},
        JSON.stringify({
            'agentId': uID,
            'sessionChatId': msg.sessionChatId
        }));
    }

    // ---------------------------------------------------------------------------------
    let divMessage = 
    "<div  onclick='highlightTicketOnclick(\"" + msg.sessionChatId + "\",\"" + clientId + "\",\"" + msg.msgOrder + "\",\"" + ((msg.seen == false) ? "1" : "0") + "\")'><div class=\"flex-between\">" +
    "      <div style='width: 100%' id='appendAfter-" + msg.sessionChatId + "'>\n" +
    "            <b style='width: 60%;font-size: 16px'class=\"sessionChatId\">" + (clientId.includes("@reeng/reeng") ? clientId.slice(0, -12) : clientId) + "</b>" +
    "            <i class='time-msg-tc'>" + timeCustom + "</i>\n" +
    "      </div>" +
    "</div>" +
    "<div style='font-size: 13px;" + (seen ? "font-weight: bold;" : "") + "' class=\"message\">" + 
    "      <span>" + message + "</span>\n" +
    "</div></div>";
    
    // đã tồn tại sc
    if (document.getElementById(status + '-' + msg.sessionChatId)) {
        
            document.getElementById(status + '-' + msg.sessionChatId).innerHTML = divMessage;

    }
    else  { // chưa tồn tại sc
        if(isOld == true){
            $("#" + location).append("<li style='height: auto ; min-height: 80px' class=\"list-group-item\" id=" + status + "-" + msg.sessionChatId + ">" +
            divMessage +
            "</li>");
        }else if(isOld == false) {
            $("#" + location + " .after-sc").after("<li style='height: auto ; min-height: 80px' class=\"list-group-item\" id=" + status + "-" + msg.sessionChatId + ">" +
            divMessage +
            "</li>");
        }
            
    }

    if(status === "WAITING"){
        $( "#appendAfter-" + msg.sessionChatId ).after( "<div class='btn-assign'>\n" +
                        "<button style='font-size: 0.6vw' class=\"btn btn-success btnAssign\" onclick=assign(\"" + msg.sessionChatId + "\")> Chấp nhận</button>\n" +
                        "</div>" );
    }
    else if(status === "APPROVE"){
        if(moreInfo != null){
            $( "#appendAfter-" + msg.sessionChatId ).after( "<div>\n" +
            "<img width='40px' height='100%' src=\"" + moreInfo.image + "\" />" +
            "</div>" );
        }
        
    }
    
    // to mau vao ticket khi day len tren
    if(sessionChatId_now != null && msg.sessionChatId === sessionChatId_now && statusTicketNow === status){
        if($("#" + statusTicketNow + "-" + msg.sessionChatId)){
            $("#" + statusTicketNow + "-" + msg.sessionChatId).css("background-color" , "#82828250");
        }
        
    }
}

async function displayTimeRecord(){
    setIntervalId = setInterval(function(){
        timeRecord++;
        let time = timeRecord%60;
        $("#recording-time").text(((timeRecord - time)/60) + ":" + ("0" + time).slice(-2));

        if(timeRecord == 1*60*60){
            mediaRecorder.stop();
        }
    } , 1000);
}

function timeConvert (time){
    
    return formatAMPM(new Date(time));
    
}

const formatAMPM = (date) => {
    let hours = date.getHours();
    let minutes = date.getMinutes();
    let ampm = hours >= 12 ? 'pm' : 'am';
    hours = hours % 12;
    hours = hours ? hours : 12;
    minutes = minutes.toString().padStart(2, '0');
    let strTime = hours + ':' + minutes + ' ' + ampm;
    return strTime;
  }

  function escapeHtml(unsafe) {
    return unsafe
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#039;");
}

function ascii (a) { return a.charCodeAt(0); }

// button assign tại giao diện waiting
function assign(sessionChatId , e) {
    if (!e) var e = window.event
    e.cancelBubble = true;
    if (e.stopPropagation) e.stopPropagation();
    
    stompClient.send("/helpdesk/notify/assign", {},
        JSON.stringify({
            'sessionChatId': sessionChatId,
            'agentId': uID,
    }));
    
    agentClickAsssign = true;
}

// lấy list chatmessage theo ticket id
function getListChat(sessionChatId) {
    stompClient.send("/helpdesk/chat-message/get", {},
        JSON.stringify({
            'sessionChatId': sessionChatId,
            'agentId': uID,
            'limit': parseInt('10'),
            'msgOrder':messageOrder ,
        }));

}

// gửi tin nhắn
function sendMessage(message , mediaLink , typeMessage , fileName , sizeFile , duration , ratio) {

    
    let contentPre;
    let classPossition = "send";
    let classLR = "right";
  
    let timeSend = new Date().getTime();
    
    if(typeMessage === "text") {

        contentPre = escapeHtml(message);

        let classTimeMsg = "time-msg-";

        let classPreText = (message.length <= 10 ? message.replace(/\s/g, '') : message.replace(/\s/g, '').slice(0 , 9))
        .split('')
        .map(ascii)
        .join("");

        let htmlOfChatMessage = "\n" +
        "                    <div class=\"msg-" + classPossition + " " + classPreText + "\">\n" +
        "                        <div class=\"content-" + classPossition + "\">\n" +
        "\n" +
        "                            <pre class=\"pre-" + classLR + "\">" + contentPre + " <span class=\"glyphicon glyphicon-repeat\"></span>" + "</pre>\n" +
        "\n" +
        "                        <i class=\"time-msg " + classTimeMsg + "\">" + timeConvert(timeSend) + "</i>\n" +

        "                        </div>\n" +
        "\n" +
        "                    </div>";

        // append icon pre send text
        $("#content-msg").append(htmlOfChatMessage);

        setTimeout(function(){
        
            $('#content-msg').scrollTop($('#content-msg')[0].scrollHeight);
    
        }, 200); 

        $('#input-text').val("");
    }

    // -------------------------------------------------------------------------------------
    stompClient.send("/helpdesk/message/" + sessionChatId_now, {},

        JSON.stringify({
            'fromUserId': uID,
            'sessionChatId': sessionChatId_now,
            'message': message,
            'duration': duration,
            'size': sizeFile,
            'ratio': ratio,
            'mediaLink': mediaLink,
            'typeMessage': typeMessage,
            'name': fileName,
            'avatar': "avatar",
            'sendAt': timeSend,
            'createdAt': timeSend
        }));

        
        // if(typeMessage != "voicemail" && typeMessage != "sharevideov2"){
        //     $('#input-text').val("");
        // }
}

// button kết thúc
function onClose() {

    if(document.getElementById('id-button2').checked == true){
        
        departmentId = $('input[name=fav_language2]:checked').val();

        if(departmentId == null){
            window.alert("Choose department please");
        }
        stompClient.send("/helpdesk/notify/close", {},
            JSON.stringify({
                'sessionChatId': sessionChatId_now,
                'agentId': uID,
                'statusClose': 'OPEN',
                'departmentId': departmentId
            }));
    }
    else {
        
        stompClient.send("/helpdesk/notify/close", {},
            JSON.stringify({
                'sessionChatId': sessionChatId_now,
                'agentId': uID,
                'statusClose': 'PENDING',
                'helpId': 1
            }));
    }
}

// thêm chat to message
function appendChatMessageToTicket(msg, isNew) {
    let classPossition;
    let classLR;
    let domain;

     // ------------------------------- append date ---------------------------------------
    
    if(oldTime == null){
        oldTime = msg.createdAt; 
    }

    if(msg.createdAt < oldTime){
        // kiem tra oldTime la ngay hom sau cua msg.createdAt -> append

        let startOfDate = new Date(oldTime);
        startOfDate.setUTCHours(0,0,0,0);

        let tmStartOfDate = startOfDate.getTime();
        if(oldTime >= tmStartOfDate && msg.createdAt < tmStartOfDate){
            $("#content-msg").prepend("<p class=\"date-day\">" + 
            new Date(oldTime).toLocaleDateString("en-UK") + "</p>");
        }
        oldTime = msg.createdAt;
    }

    // ----------------------------------------------------------------------------------------

    if(msg.fromType === "MOBILE"){
        classPossition = "re";
        classLR = "left";
        domain = "https://cmscamid.ringme.vn:8443";
    } else if(msg.fromType === "CMS" || msg.fromType === "SYSTEM" || msg.fromType === "DEPARTMENT"){
        classPossition = "send";
        classLR = "right";
        domain = "https://cmscamid.ringme.vn:8443";
    }

    let contentPre;
    if(msg.fromType === "DEPARTMENT"){
        contentPre = "<strong>HƯỚNG DẪN TỪ PHÒNG BAN : </strong> \n" + msg.message;
    }
    else if(msg.typeMessage === "image"){
        contentPre = "<img alt='not found' class=\"msg-img\" src=\"" + domain + msg.mediaLink + "\" />";
    }
    else if(msg.typeMessage === "file_2"){
        
        let ext = msg.name.substring(msg.name.lastIndexOf(".")+1 , msg.name.length);

        if(ext === "mp3" || ext === "aac"){
            contentPre = "<audio class=\audioPlay\" style=\"height: 45px;\" controls>" + 
            "<source src=\"" + domain + msg.mediaLink + "\">" + 
        "</audio>";
        }else if(ext === "mp4"){
            contentPre = "<video width='200' height='auto' id=\"videoPlay\" controls>" + 
            "<source src=\"" + domain + msg.mediaLink + "\">" + 
        "</video>";
        }else {
            contentPre = "<a style=\"color: black !important;\" href=\"" + domain + msg.mediaLink + 
            "\" width=\"auto\" ><span class=\"glyphicon glyphicon-file file-msg-icon\"></span>" + 
            msg.name + "</a>";
        }
        
    }
    else if(msg.typeMessage === "voicemail"){
        contentPre = "<audio class=\audioPlay\" style=\"height: 45x;\" controls>" + 
            "<source src=\"" + domain + msg.mediaLink + "\">" + 
        "</audio>";
    }else if(msg.typeMessage === "sharevideov2"){
        contentPre = "<video width='200' height='auto' id=\"videoPlay\" controls>" + 
        "<source src=\"" + domain + msg.mediaLink + "\">" + 
      "</video>";
    }
    else if(msg.typeMessage === "report"){
        let report = JSON.parse(msg.message);
        contentPre = "<pre>" + "<strong>Report a problem</strong>" +
                "<p>Description : " + escapeHtml(report.description) + "</p>";
        
        if(report.mediaLinks.length > 0){
            report.mediaLinks.forEach(function(mediaLink , index){

                contentPre = contentPre + "<img class=\"msg-img\" alt='not found' class=\"msg-img\" src=\"" + mediaLink + "\" />";
                    if(index < mediaLink.length){
                        
                        contentPre = contentPre + "<br>";
                    }                
            });
        }
        
        if(report.errorLink != ""){
            contentPre = contentPre + "<p>Error link : <a style='color: blue !important;' href=\"" + report.errorLink + "\" > " + report.errorLink + "</a></p>" + 
        "</pre>";
        }
        
    }
    else {
        contentPre = "<p>" + escapeHtml(msg.message) + "</p>";
    }
    
    let classTimeMsg = "time-msg-" + msg.id;

    let htmlOfChatMessage = "\n" +
    "                    <div class=\"msg-" + classPossition + "\">\n" +
    "                        <div " + (msg.fromType === "DEPARTMENT" ? "style=\"background-color: #f7d577;\"" : "") + " class=\"content-" + classPossition + "\">\n" +
    "\n" +
    "                            <pre class=\"pre-" + classLR + "\">" + contentPre + "</pre>\n" +
    "\n" +
    "                        <i class=\"time-msg " + classTimeMsg + "\">" + timeConvert(msg.createdAt) + "</i>\n" +

    "                        </div>\n" +
    "\n" +
    "                    </div>";

    if(isNew){
        
        let checkIsBottom = ($('#content-msg').scrollTop() + $('#content-msg')[0].clientHeight) >= $('#content-msg')[0].scrollHeight;
        $("#content-msg").append(htmlOfChatMessage);
        
        if(msg.fromType === "CMS"){
            let classPreview;
            if(msg.typeMessage === "text"){
                classPreview = "." + (msg.message.length <= 10 ? msg.message.replace(/\s/g, '') : msg.message.replace(/\s/g, '').slice(0 , 9)).split('').map(ascii).join("");
            }else {
                classPreview = ".loading-file-" + msg.typeMessage;
            }
            if($(classPreview).length > 0){
                $(classPreview)[0].remove();
            }
        }else 
        if(msg.fromType === "MOBILE" && checkIsBottom){
            
            setTimeout(function(){
        
                $('#content-msg').scrollTop($('#content-msg')[0].scrollHeight);
        
            }, 200); 
        }
    }else {
        $("#content-msg").prepend(htmlOfChatMessage);
    }
   

    // -------------------------------------------------------------------------------
    if(msg.typeMessage === "image" || msg.typeMessage === "report"){
        $(".msg-img").click(function(){
        
            $("#myModal").show();
            $("#img01").attr('src' , this.src);
    
        });

        setTimeout(function(){
        
            $('#content-msg').scrollTop($('#content-msg')[0].scrollHeight);
    
        }, 200); 
    }

    // ------------------------------------------------------------------------------------
    if(msg.msgOrder == 1){
        $("#content-msg").prepend("<p class=\"date-day\">" + 
        new Date(msg.createdAt).toLocaleDateString("en-UK") + "</p>");
        
    }
    
}

// xử lý sự kiện onlick vào ticket
function highlightTicketOnclick(sessionChatId , userId , msgOrder , seen) {
    // ******
    const url = new URL(window.location);
    url.searchParams.set('session_chat_id' , sessionChatId);
    window.history.pushState({}, '', url);

    removeFileData();
    removeVoiceData();
    $('#input-text').val("");

    oldTime = null;

    if(sessionChatId_now == null){
        sessionChatId_now = sessionChatId;
        
        document.getElementById(statusTicketNow + "-" + sessionChatId_now).style.backgroundColor='#82828250'
    }
    else if(sessionChatId_now !=null && sessionChatId_now != sessionChatId){
        
        document.getElementById(statusTicketNow + "-" + sessionChatId_now).style.backgroundColor='white'
        document.getElementById(statusTicketNow + "-" + sessionChatId).style.backgroundColor='#82828250'
    }
    else {
        return;
    }
    sessionChatId_now = sessionChatId;

    messageOrder = parseInt(msgOrder) + 1;
    
    document.getElementById("chat-tab-id").style.display = 'block';

    if(statusTicketNow === "ASSIGNED"){
        document.getElementById("block-send-msg").style.display = 'block';
        document.getElementById("btn-close-session-helpdesk").style.display = 'block';
    } else {
        document.getElementById("block-send-msg").style.display = 'none';
        document.getElementById("btn-close-session-helpdesk").style.display = 'none';
        
    }

   document.getElementById("content-msg").innerHTML = '';
    document.getElementById('user-info-chat').innerHTML =  "<strong style=\"font-size: 14px;\">" + (userId.includes("@reeng/reeng") ? userId.slice(0, -12) : userId) + "</strong><br>" + 
    "<span style=\"font-size: 12px;\"> " + "Người dùng CamID" + "</span>";

    setTimeout(function(){
        
        getListChat(sessionChatId);

    }, 100); 

    setTimeout(function(){
        
        $('#content-msg').scrollTop($('#content-msg')[0].scrollHeight);
    }, 500); 
    
    if(seen == 1 && (statusTicketNow === "ASSIGNED" || statusTicketNow === "OPEN")){
        stompClient.send("/helpdesk/chat-message/seen", {},
        JSON.stringify({
            'agentId': uID,
            'sessionChatId': sessionChatId
        }));
    }
    
}

// load more
function loadMoreTopToBottomClassTabContent() {
    
    
    $('.tab-content').scroll(function () {

        var content_scroll=$('.tab-content .active').attr('id');

        let div = $(".tab-content").get(0);
        if(div.scrollTop + div.clientHeight >= div.scrollHeight) {
            let check;
            let searchTerm;

            if (content_scroll == 'home') {
                searchTerm = $("#home .search-sessionchat").val();
                strStatus = "WAITING";
                check = countWaiting;
                lastSSC=$("#home .list-group-item").last().attr('id').split("-")[1];
            }
            else if (content_scroll == 'menu1') {
                searchTerm = $("#menu1 .search-sessionchat").val();
                strStatus = "ASSIGNED";
                check = countAssigned;
                lastSSC=$("#menu1 .list-group-item").last().attr('id').split("-")[1];
            }
            else if (content_scroll == 'menu2') {
                searchTerm = $("#menu2 .search-sessionchat").val();
                strStatus = "OPEN";
                check = countOpen;
                lastSSC=$("#menu2 .list-group-item").last().attr('id').split("-")[1];
            }
            else if (content_scroll == 'menu3') {
                searchTerm = $("#menu3 .search-sessionchat").val();
                strStatus = "PENDING";
                check = countPending;
                lastSSC=$("#menu3 .list-group-item").last().attr('id').split("-")[1];
            }
            else if (content_scroll == 'menu4') {
                searchTerm = $("#menu4 .search-sessionchat").val();
                strStatus = "APPROVE";
                check = countApprove;
                lastSSC=$("#menu4 .list-group-item").last().attr('id').split("-")[1];
            }

            if(countLoadmoreSc == check && searchTerm == ""){
                return;
            }

            stompClient.send("/helpdesk/session-chat/get", {},
                JSON.stringify({
                    'agentId': uID,
                    'status': strStatus,
                    'limit': 10,
                    "lastScId": lastSSC,
                    "termSearch": searchTerm != "" ? searchTerm : null
                }));
        }
    });

}

// load more
function loadMoreBottomToTopClassTabContent() {

    $('.content-msg').scroll(function () {
        
        if(messageOrder == 1){
            return;
        }
        let div = $(".content-msg").get(0);
        let height = div.scrollHeight;

        if(div.scrollTop == 0) {
            
            getListChat(sessionChatId_now);

            setTimeout(function(){
        
                $('#content-msg').scrollTop(div.scrollHeight - height);
        
            }, 300); 
        
        }
    });

}

function sendFileToServer(type) {
    
    if(type === "voice"){
        var file = null;

        if(audioBlob != null){

            const voice_name = "voicemail:" + uID + ":" + sessionChatId_now ;
            file = new File([audioBlob], voice_name + ".voicecall",
            { lastModified: new Date().getTime(), type: audioBlob.type });;
            
            const typeMessage = "voicemail";

            removeVoiceData();

            // ------------------------------------------------------------------------------------------
            let input = "Sending " + type + " ...";
            let contentPre;
            let classPossition = "send";
            let classLR = "right";
        
            let timeSend = new Date().getTime();
            
            
            contentPre = input;

            let classTimeMsg = "time-msg-";

            let htmlOfChatMessage = "\n" +
            "                    <div class=\"msg-" + classPossition + " loading-file-" + typeMessage + "\">\n" +
            "                        <div class=\"content-" + classPossition + "\">\n" +
            "\n" +
            "                            <pre class=\"pre-" + classLR + "\">" + contentPre + " <span id=\"icon-loading-file-" + file.size + "\" class=\"glyphicon glyphicon-repeat\"></span>" + "</pre>\n" +
            "\n" +
            "                        <i class=\"time-msg " + classTimeMsg + "\">" + timeConvert(timeSend) + "</i>\n" +

            "                        </div>\n" +
            "\n" +
            "                    </div>";


            // append icon preview file
            $("#content-msg").append(htmlOfChatMessage);
            setTimeout(function(){
                
                        $('#content-msg').scrollTop($('#content-msg')[0].scrollHeight);
                
                    }, 200); 


            // ------------------------------------------------------------------------------------------
        
            var formData = {
                uFile: file,
                sessionChatId: sessionChatId_now,
                timestamp: timeSend,
                mpw: 1,
                userId : uID
            };
        
            
            axios.post('https://cmscamid.ringme.vn:8443/helpdesk-camid-service/api/v1/upload', formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            }).then(function (response) {
                
                sendMessage(null , response.data.data.mediaPath , typeMessage , 
                    voice_name + ".aac" , file.size , timeRecord , null);
            
                }).catch(function (error) {
                    $("#icon-loading-file-" + file.size).removeClass("glyphicon-repeat").addClass("glyphicon-alert");
                    $("#icon-loading-file-" + file.size).removeAttr('id');
                });
        }
    }else if(type === "file"){
        if(listFileUpload != null){
            
            listFileUpload.forEach(function(file){
                
                let typeMessage;
                if(file.type.includes("image/")){
                    typeMessage = "image";
                }else if(file.type.includes("video/")){
                    typeMessage = "sharevideov2";
                }
                else {
                    typeMessage = "file_2";
                }                

                // ----------------- add duration
                let duration = null;
                if(typeMessage === "sharevideov2"){
                    var vid = document.createElement('video');
                    // create url to use as the src of the video
                    var fileURL = URL.createObjectURL(file);
                    vid.src = fileURL;
                    // wait for duration to change from NaN to the actual duration
                    vid.ondurationchange = function() {
                        duration = vid.duration;
                        
                    };
                }

                // -----------------------add ratio
                let ratio = null;
                if(typeMessage === "image"){
                        let img = document.getElementById("img-" + file.size);
                        let height = img.naturalWidth;
                        let width = img.naturalHeight;
    
                        ratio = (height/width).toFixed(2);
                        console.log(ratio);
                }
                // ------------------------------------------------------------------------------------------
                let input = "Sending " + type + " ...";
                let contentPre;
                let classPossition = "send";
                let classLR = "right";
            
                let timeSend = new Date().getTime();
                
                
                contentPre = input;

                let classTimeMsg = "time-msg-";

                let htmlOfChatMessage = "\n" +
                "                    <div class=\"msg-" + classPossition + " loading-file-" + typeMessage + "\">\n" +
                "                        <div class=\"content-" + classPossition + "\">\n" +
                "\n" +
                "                            <pre class=\"pre-" + classLR + "\">" + contentPre + " <span id=\"icon-loading-file-" + file.size + "\" class=\"glyphicon glyphicon-repeat\"></span>" + "</pre>\n" +
                "\n" +
                "                        <i class=\"time-msg " + classTimeMsg + "\">" + timeConvert(timeSend) + "</i>\n" +

                "                        </div>\n" +
                "\n" +
                "                    </div>";


                // append icon preview file
                $("#content-msg").append(htmlOfChatMessage);
                setTimeout(function(){
                    
                            $('#content-msg').scrollTop($('#content-msg')[0].scrollHeight);
                    
                        }, 200); 
                
                
                // ------------------------------------------------------------------------------------------
                var formData = {
                    uFile: file,
                    sessionChatId: sessionChatId_now,
                    timestamp: timeSend,
                    mpw: 1,
                    userId : uID
                };
            
                axios.post('https://cmscamid.ringme.vn:8443/helpdesk-camid-service/api/v1/upload', formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                }).then(function (response) {
                    
                    sendMessage(null , response.data.data.mediaPath , 
                    typeMessage, file.name, file.size , duration , ratio);
            
                    
                }).catch(function (error) {
                    $("#icon-loading-file-" + file.size).removeClass("glyphicon-repeat").addClass("glyphicon-alert");
                    $("#icon-loading-file-" + file.size).removeAttr('id');
                });
    
            });
            
            removeFileData(null);   
        }
    }
  
    
}

function removeVoiceData(){
    recordAudio = false;

    if(mediaRecorder){
        mediaRecorder.stop();
    }
    $("#pre-audio").trigger("pause");
    $("#pre-audio").attr("src", "");
    $("#pre-audio").hide();
    $(".custom-close-pre-audio").hide();
    audioBlob = null;

    // stop recording
    // mediaRecorder = null;
    inputType = "text";
    timeRecord = 0;
    $("#recording-time").text("0:00");

    $('.send-voice').parent().show();
    $("#input-text").show();
    $(".custom-file-upload").show();

    $(".pause-recorder").hide();
    $(".loading-recoder").hide();

    
}

function removeFileData(filekey){
    
    if(filekey != undefined && filekey != null){
    
        if($("#" + filekey)){
            
            $("#" + filekey).remove();
        }
        
        let removeSuccess = false;
        if(listFileUpload != null && listFileUpload.length > 0){
    
            listFileUpload.forEach(function(file , index){

                if(removeSuccess == false && file.size == filekey){

                    listFileUpload.splice(index , 1);

                    removeSuccess = true;
                }
            });
    
            
    
            if(listFileUpload.length == 0){

                inputType = "text";
                listFileUpload = null;
                $("#send-file-to-form").val(null);
                $("#list-pre-file-upload").hide();
            }
        }
    }else {
        
        
        if(listFileUpload != null && listFileUpload.length > 0){
    
            listFileUpload.forEach(function(file , index){

                if($("#" + file.size)){
            
                    $("#" + file.size).remove();
                }

            });
    
            inputType = "text";
            listFileUpload = null;
            $("#send-file-to-form").val(null);
            $("#list-pre-file-upload").hide();
            
        }
    }
    
}

function checkAndSendMessage(){
    if($("#send-file-to-form")[0].files.length > 0){
        inputType = "file";
    } else if(audioBlob != null){
        inputType = "voice";
    }

    if(inputType != "text"){
        sendFileToServer(inputType);
    }
    if ($("#input-text").val().trim() != "") {
        $("#input-text").css("height" , "40px");
        sendMessage($("#input-text").val().trim() , null , "text" , null , null , null);
    
    }
    
}

$(function () {
    connect();

    $('.user-index').hide();
    $('#loader').show();
    $("#myModal").hide();
    $("#list-pre-file-upload").hide();
    $("#pre-img").hide();
    $("#pre-file").hide();
    $("#pre-name-file").hide();

    $('.list-group-item').dblclick(false);

    $("btn-close2").click(function (){
       close();
    });

    // When the user clicks on <span> (x), close the modal
    $(".close-zoom-img").click(function(){
        $("#myModal").hide();
    });

    // --------------------------------------------------------------------------------
    loadMoreTopToBottomClassTabContent();
    loadMoreBottomToTopClassTabContent();

    // --------------------------------- preview file ---------------------------------
    
    $("#send-file-to-form").change(function(e) {
        
        if (e.target.files.length > 0){

            listFileUpload = Array.from(e.target.files);
            $("#list-pre-file-upload").show();
            
            listFileUpload.forEach((file) => {
            
                let filename = file.name;
                let src;

                let filekey = file.size;

                let preThe;
                if(file.type.includes("image/")){

                    src = URL.createObjectURL(file);

                    preThe = "<div class=\"pre-file-div\" id=\"" + filekey +  "\">" + 
                    "<button onclick=removeFileData(\"" + filekey + "\") class=\"cl-pre-image\">" + 
                    "<span>&times;</span>" + 
                    "</button>" + 
                    "<img id=\"img-" + filekey + "\" class=\"msg-img pre-img\" src=\"" + src + 
                    "\" style=\"padding: 10px;\">\n" + 
                    "<div>";

                }
                else {
                    preThe = "<div class=\"pre-file-div\" id=\"" + filekey +  "\">" + 
                    "<button onclick=removeFileData(\"" + filekey + "\") class=\"cl-pre-image\">" + 
                    "<span>&times;</span>" + 
                    "</button>" + 
                    "<span class=\"pre-file glyphicon glyphicon-file\"></span>\n" +  
                    "<p class=\"pre-name-file\">" + filename + "</p>\n" + 
                    
                    "<div>";
                }
                
                $("#list-pre-file-upload").append(preThe);
                
                
            });
            $(".msg-img").click(function(){
        
                $("#myModal").show();
                $("#img01").attr('src' , this.src);
        
            });
        }
    });


    // --------------------------------- preview audio ---------------------------------
    
    $("#cl-pre-audio").click(function() {
        
        removeVoiceData();
        $(".custom-close-pre-audio").hide();
    });

    // ------------------------------------- search ---------------------------------------------
    
    $(".search-sessionchat").on('input', function (e) {
        
        setTimeout(function(){
            let statusNumber;

            if(statusTicketNow == "WAITING" ){
                statusNumber = 1;
                getedWaiting = false;
            }
            else if(statusTicketNow === "ASSIGNED" ){
                statusNumber = 2;
                getedAssigned = false;
            }
            else if(statusTicketNow === "OPEN" ){
                statusNumber = 3;
                getedOpen = false;
            }
            else if(statusTicketNow === "PENDING"){
                statusNumber = 4;
                getedPending = false;
            }
            else if(statusTicketNow === "APPROVE"){
                statusNumber = 5;
                getedApprove = false;
            }
            
            getListSessionChatByStatus(statusNumber);
        }, 300); 
    });

    // ------------------------------------ mo rong input chat khi xuong dong -----------------
    $("#input-text").each(function () {
        $("#input-text").css("height" , (this.scrollHeight) + "px");
      }).on("input", function () {
        $("#input-text").css("height" , "auto");
        $("#input-text").css("height" , (this.scrollHeight) + "px");
      });

    

    // -------------------------------------------recoder audio -------------------------------
    $('.send-voice').click(function(){
        

        if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
            alert('Your browser does not support recording!');
            return;
          }
          let type = 'audio/webm;codecs="opus"';

        //   $(".custom-file-upload").hide();
        //   $("#input-text").hide();
          $('.send-voice').parent().hide();
          $(".custom-close-pre-audio").show();

          $(".pause-recorder").show();
          $(".loading-recoder").show();
          let audioURL = null;
          

          if (!mediaRecorder) {
            // start recording
            navigator.mediaDevices.getUserMedia({
              audio: true,
            })
              .then((stream) => {
                const options = {
                    audioBitsPerSecond: 128000,
                    mimeType: type
                  }
                mediaRecorder = new MediaRecorder(stream , options);

                
                if(MediaRecorder.isTypeSupported(type)){
                    mediaRecorder.start();
                    recordAudio = true;
                    displayTimeRecord();
                    mediaRecorder.ondataavailable = function (e) {
                        chunks.push(e.data);
                    };
                    mediaRecorder.onstop = function(){

                        
                        $(".loading-recoder").hide();

                        clearTimeout(setIntervalId);

                        if(recordAudio == true){
                            
                            $("#pre-audio").show();
                            // $(".custom-close-pre-audio").show();
                            
                            //create the Blob from the chunks
                            audioBlob = new Blob(chunks, { type: mediaRecorder.mimeType });
                            audioURL = window.URL.createObjectURL(audioBlob);
                            
                            $("#pre-audio").attr("src", audioURL);
                            $("#pre-audio").attr("type", mediaRecorder.mimeType );
                        }
                        
                        
                        //reset to default
                        mediaRecorder = null;
                        chunks = [];

                        var tracks = stream.getTracks();
                            tracks.forEach(function(track){
                            track.stop();
                        });
                    
                };
                }
                
              })
              .catch((err) => {
                alert(`The following error occurred: ${err}`);
              });
          }
    });
    
    $(".pause-recorder").click(function(){
        // stop recording
        mediaRecorder.stop();
    })

      //--------------------------------------- su kien enter vao input chat ------------------------
      $("#input-text").on('keypress', function (e) {
        
        if(e.ctrlKey && e.which == 10){
            
            $('#input-text').val($('#input-text').val() + "\n");
        }
        else if (e.which == 13) {
            e.preventDefault();
            checkAndSendMessage();
        }
        
    });

    // ---------------------------------------submit chat ------------------------------------------
    $("form").submit(function (event) {
        event.preventDefault();

        checkAndSendMessage();
     
      });
    
      // ----------------------------out websocket when close ---------------------------------------
    $(window).on('beforeunload', function(){
        if (stompClient !== null) {
            stompClient.disconnect();
            
        }
    });
});
