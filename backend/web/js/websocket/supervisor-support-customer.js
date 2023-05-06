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

var countStatusNow = 0;
var countLoadmoreSc = 0;

var oldTime = null;

var getedWaiting = false;
var getedAssigned = false;
var getedOpen = false;
var getedPending = false;
var getedApprove = false;

var isRemoveForSearch = false;

function connect() {

    var socket = new SockJS('https://cmscamid.ringme.vn:8443/helpdesk-camid-service/helpdesk/event');
    stompClient = Stomp.over(socket);
    // stompClient.debug = () => {};
    stompClient.connect({"user": uID, "X-Authorization": token}, function (frame) {
        $('.user-index').show();
        $('#loader').hide();

        // --------------------------------- subcribe /user/waiting ---------------------
        subscription_id = stompClient.subscribe('/user/watching', function (frame) {

            const msg = JSON.parse(JSON.parse(frame.body));

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
                        "APPROVE" , element.clientId, true , element.rateLevel , element.timeAssign, element.finishedAt);
                    });
                }
                
            }
            else if(msg.type != null && msg.type === "getListCm"){
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
            else if(msg.type === "survey"){
                window.alert(msg.type + "-" + msg.status);
            }
            else if(msg.desc != null && msg.desc === "countSc"){
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
        });

        stompClient.send("/helpdesk/session-chat/count", {},
            JSON.stringify({
            'agentId': (supervisor_sp == true ? null : uID)
        })
        );
        

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
        
        // --------------------------------- subcribe /watching/waiting ---------------------
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

        // --------------------------------- subcribe /watching/supervisor ---------------------
        subscription_id = stompClient.subscribe((supervisor_sp == true ? '/watching/supervisor' : ('/watching/assigned/' + uID)), 
        function (frame) {
            const msg = JSON.parse(JSON.parse(frame.body));

            if(msg.type === "assign"){
                if(msg.status === "success"){

                    countAssigned = countAssigned + 1;
                    $("#countAssigned").text("(" + countAssigned + ")");

                    checkAndAddNewTicketToStatusTab('ASSIGNED' , msg.lastMsg , 
                        msg.lastMsg.fromUserId , "menu1" , null);

                }
            }
            else if(msg.type === "close"){
                // close session chat 
                if(msg.status === "success"){

                    countAssigned = countAssigned - 1;
                    $("#countAssigned").text("(" + countAssigned + ")");

                    checkAndRemoveTicketInStatusTab("ASSIGNED" , msg.sessionChat.id);

                    let sessionChatStatus = msg.sessionChat.status;

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

                    checkAndAddNewTicketToStatusTab("ASSIGNED" , msg.sessionChat.chatMessage , 
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
                if(msg.status === "ASSIGNED"){    
                    appendTicketToAnotherStatus(msg.chatMessage , "menu1" , "ASSIGNED", 
                    msg.clientId , false , null);
                } 
                else if(msg.status === "OPEN"){    
                    appendTicketToAnotherStatus(msg.chatMessage , "menu2" , "OPEN", 
                    msg.clientId , false , msg.departmentHelp);
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
            'agentId': (supervisor_sp == true ? null : uID),
            'status':strStatus,
            'limit':20,
            "lastScId":null,
            "termSearch": searchTerm != "" ? searchTerm : null
    }));

}


// thêm ticket to another status
function appendTicketToAnotherStatus(msg, location, status, clientId, isOld , moreInfo , tassign , tfinish) {
    
    if($("#" + status + "-" + msg.sessionChatId)){
        $("#" + status + "-" + msg.sessionChatId).remove();
    }
    countLoadmoreSc++;

    let message = "";
    if(msg.typeMessage === "report"){
        message = "<span style=\"color:#a84032;\">Report from client</span>";
    }
    else if(msg.typeMessage != "text"){
        // let oldMsg = msg.message;
        // let n = msg.message.lastIndexOf('/');
        // message = msg.message.substring(n + 1);
        let typeFile;

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

    let checkbox = "";

    if (status === "WAITING") {
        checkbox = "<input type=\"checkbox\" class=\"check-send-waitting\" value=" + msg.sessionChatId + ">&ensp;";
    }else if (status === "PENDING") {
        checkbox = "<input type=\"checkbox\" class=\"check-send-pending\" value=" + msg.sessionChatId + ">&ensp;";
    }

    // let checkTime = "";
    // if(tassign != null && tfinish != null){
    //     let timeAssign =  (new Date(tassign).toLocaleDateString("en-UK")) + 
    //     " " + timeConvert(tassign);
    //     let timeFinish =  (new Date(tfinish).toLocaleDateString("en-UK")) + 
    //     " " + timeConvert(tfinish);

    //     checkTime = "<br><strong>timeAssign :</strong> " + timeAssign + 
    //     " <br><strong>timeFinish :</strong> " + timeFinish + 
    //     "<br><strong>time support(minutes) :</strong> " + (tfinish - tassign)/60000; 
    // }
    

    let divMessage =
        "<div  onclick='highlightTicketOnclick(\"" + msg.sessionChatId + "\",\"" + clientId + "\",\"" + msg.msgOrder + "\")'><div class=\"flex-between\">" +
        "      <div style='width: 100%' id='appendAfter-" + msg.sessionChatId + "'>\n" +
        "            " + checkbox + "<b style='width: 60%;font-size: 16px'class=\"sessionChatId\">" + (clientId.includes("@reeng/reeng") ? clientId.slice(0, -12) : clientId) + "</b>" +
        "            <i class='time-msg-tc'>" + timeCustom + "</i>\n" +
        "      </div>" +
        "</div>" +
        "<div style='font-size:13px' class=\"message\">" +
        "      <span>" + message + "</span>\n" +
        // "      <p>" + checkTime + "</p>\n" +
        "</div>";

    // đã tồn tại sc
    if (document.getElementById(status + '-' + msg.sessionChatId)) {
        
            document.getElementById(status + '-' + msg.sessionChatId).innerHTML = divMessage;

    } 
    else  { // chưa tồn tại sc
        if(isOld){
            $("#" + location).append("<li style='height: auto ; min-height: 80px' class=\"list-group-item\" id=" + status + "-" + msg.sessionChatId + ">" +
            divMessage +
            "</li>");
        }else {
            $("#" + location + " .after-sc").after("<li style='height: auto ; min-height: 80px' class=\"list-group-item\" id=" + status + "-" + msg.sessionChatId + ">" +
                divMessage +
                "</li>");
        }
        
        $("input[type=checkbox]").click(function(e) {
            if (!e) var e = window.event
            e.cancelBubble = true;
            if (e.stopPropagation) e.stopPropagation();
          });
    }

    if(status === "APPROVE"){
        if(moreInfo != null){
            $( "#appendAfter-" + msg.sessionChatId ).after( "<div>\n" +
            "<img width='40px' height='100%' src=\"" + moreInfo.image + "\" />" +
            "</div>" );
        }   
    }
    else if(status === "OPEN"){
        if(departmentId == moreInfo.departmentId){
            $( "#appendAfter-" + msg.sessionChatId ).after( "<a href=\"/department-help/update?_id=" + 
            moreInfo.id + "\"><button style='font-size: 0.6vw' class=\"btn btn-success\">Xử lý</button><a>" );
            
        }
        
    }
    if(msg.sessionChatId === sessionChatId_now){
        document.getElementById(statusTicketNow + "-" + sessionChatId_now).style.backgroundColor='#82828250'
    }

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

    // ---------------------------------------------------------------------------------

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
        contentPre = "<img alt='not found' class=\"msg-img\" src=\"" + domain + msg.mediaLink + "\" />" + 
        "<br><p>" + (msg.message != null ? escapeHtml(msg.message) : "") + "</p>";
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
        contentPre = "<audio class=\audioPlay\" style=\"height: 45px;\" controls>" + 
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
            setTimeout(function(){
        
                $('#content-msg').scrollTop($('#content-msg')[0].scrollHeight);
        
            }, 200); 
        }else if(msg.fromType === "MOBILE" && checkIsBottom){
            setTimeout(function(){
        
                $('#content-msg').scrollTop($('#content-msg')[0].scrollHeight);
        
            }, 200); 
        }
    }else {
        $("#content-msg").prepend(htmlOfChatMessage);
    }
    
    if(msg.typeMessage === "image" || msg.typeMessage === "report"){
        $(".msg-img").click(function(){
        
            $("#myModal").show();
            
            $("#img01").attr('src' , this.src);
    
        });
    }

    // ------------------------------------------------------------------------------------
    if(msg.msgOrder == 1){
        $("#content-msg").prepend("<p class=\"date-day\">" + 
        new Date(msg.createdAt).toLocaleDateString("en-UK") + "</p>");
        
    }
}

// xử lý sự kiện onlick vào ticket
function highlightTicketOnclick(sessionChatId , userId , msgOrder) {
    
    // ******
    const url = new URL(window.location);
    url.searchParams.set('session_chat_id' , sessionChatId);
    window.history.pushState({}, '', url);

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


   document.getElementById("content-msg").innerHTML = '';
    document.getElementById('user-info-chat').innerHTML =  "<strong style=\"font-size: 14px;\">" + (userId.includes("@reeng/reeng") ? userId.slice(0, -12) : userId) + "</strong><br>" + 
    "<span style=\"font-size: 12px;\"> " + "Người dùng CamID" + "</span>";

    setTimeout(function(){
        
        getListChat(sessionChatId);

    }, 100); 

    setTimeout(function(){
        
        $('#content-msg').scrollTop($('#content-msg')[0].scrollHeight);
    }, 500); 
    
}

function loadMoreTopToBottomClassTabContent() {
    
    
    $('.tab-content').scroll(function () {

        var content_scroll=$('.tab-content .active').attr('id');

        let div = $(".tab-content").get(0);
        if(div.scrollTop + div.clientHeight + 1 >= div.scrollHeight) {
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

            if(countLoadmoreSc == check){
                return;
            }

            stompClient.send("/helpdesk/session-chat/get", {},
                JSON.stringify({
                    'agentId': (supervisor_sp == true ? null : uID),
                    'status': strStatus,
                    'limit': 10,
                    "lastScId": lastSSC,
                    "termSearch": searchTerm != "" ? searchTerm : null
                }));
        }
    });

}

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

$(function () {

    $('.user-index').hide();
    $('#loader').show();

    $("#myModal").hide();

    // When the user clicks on <span> (x), close the modal
    $(".close-zoom-img").click(function(){
        $("#myModal").hide();
    });
    
    loadMoreTopToBottomClassTabContent();
    loadMoreBottomToTopClassTabContent();

    $('.list-group-item').dblclick(false);

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

    connect();
    checkWaitting();
    checkPending();


    $(window).on('beforeunload', function(){
        if (stompClient !== null) {
            stompClient.disconnect();
        }
    });
      
});

function checkWaitting() {

    // select all
    $('#check-all-waitting').click(function (event) {
        if (this.checked) {
            // Iterate each checkbox
            $('.check-send-waitting:checkbox').each(function () {
                this.checked = true;
            });
        } else {
            $('.check-send-waitting:checkbox').each(function () {
                this.checked = false;
            });
        }
    });

    // chuyen tiep cua agent
    $('#send-waiting-ssc-agent').click(function (event) {
        if($('input[name=agent]:checked').length == 0 && isAddChat != true){
            return;
        }

        var listsscid = [];
        $('.check-send-waitting:checked').each(function () {
            // listsscid += $(this).val() + ',';
            listsscid.push( $(this).val());
            // assign($(this).val());
        });
        
        
        $data = {
            "agentId": (supervisor_sp == true ? ($('input[name=agent]:checked').val()) : uID),
            "listSessionChatId": listsscid,
            "supervisorId": agent_id
        }

          postData('https://cmscamid.ringme.vn:8443/helpdesk-camid-service/api/v1/supervisor/assign', $data)
        .then(data => {

          $('input[name=agent]:checked').prop('checked', false);
          $("#id01").hide();

          if($('.check-send-waitting:checked').length ==0 || 
            $('.check-send-waitting:checked').length < $('.check-send-waitting').length){
            $('#check-all-waitting').prop('checked', false);
            }
        });
    });



}

function checkPending() {

    $('#check-all-pending').click(function (event) {
        if (this.checked) {
            // Iterate each checkbox
            $('.check-send-pending:checkbox').each(function () {
                this.checked = true;
            });
        } else {
            $('.check-send-pending:checkbox').each(function () {
                this.checked = false;
            });
        }
    });

    $('#send-app-ssc').click(function (event) {
        var listsscid = [];
        if ($('.check-send-pending:checked').length == 0) {
            return;
        }

        $('.check-send-pending:checked').each(function () {
            listsscid.push( $(this).val());
            //assign($(this).val());
        });
        
        $data = {
            "status": "ASSIGNED",
            "listSessionChatId":listsscid,
            "supervisorId": agent_id
        }
          postData('https://cmscamid.ringme.vn:8443/helpdesk-camid-service/api/v1/supervisor/approve', $data)
        .then(data => {

          if($('.check-send-pending:checked').length == 0 || 
          $('.check-send-pending:checked').length < $('.check-send-pending').length){
            $('#check-all-pending').prop('checked', false);
        }
        });
    });

    $('#send-disapp-ssc').click(function (event) {
        var listsscid = [];

        if ($('.check-send-pending:checked').length == 0) {
            return;
        }
        
        $('.check-send-pending:checked').each(function () {
            listsscid.push( $(this).val());
            
        });
        
        $data = {
            "status": "APPROVE",
            "listSessionChatId":listsscid,
            "supervisorId": agent_id
        }

          postData('https://cmscamid.ringme.vn:8443/helpdesk-camid-service/api/v1/supervisor/approve', $data)
        .then(data => {

          if($('.check-send-pending:checked').length == 0 || 
          $('.check-send-pending:checked').length < $('.check-send-pending').length){
            $('#check-all-pending').prop('checked', false);
        }
        });
    });

}

// Example POST method implementation:
async function postData(url = '', data = {}) {
    // Default options are marked with *
    const response = await fetch(url, {
        method: 'POST', // *GET, POST, PUT, DELETE, etc.
        mode: 'cors', // no-cors, *cors, same-origin
        cache: 'no-cache', // *default, no-cache, reload, force-cache, only-if-cached
        credentials: 'same-origin', // include, *same-origin, omit
        headers: {
            'Content-Type': 'application/json'
            // 'Content-Type': 'application/x-www-form-urlencoded',
        },
        redirect: 'follow', // manual, *follow, error
        referrerPolicy: 'no-referrer', // no-referrer, *no-referrer-when-downgrade, origin, origin-when-cross-origin, same-origin, strict-origin, strict-origin-when-cross-origin, unsafe-url
        body: JSON.stringify(data) // body data type must match "Content-Type" header
    });
    return response.json(); // parses JSON response into native JavaScript objects
}
