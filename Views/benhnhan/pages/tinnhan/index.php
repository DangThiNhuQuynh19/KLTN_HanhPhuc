<?php
session_start();
if (!isset($_SESSION['user']['tentk'])) {
    header("Location: index.php");
    exit();
}
$tentk = $_SESSION['user']['tentk'];
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Trò chuyện với Bác sĩ</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
   
    <style>
        body {
            background-color: #f0f2f5;
            padding-top: 90px;
            font-family: Arial, sans-serif;
        }
        .chat-layout {
            display: flex;
            height: calc(100vh - 100px);
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        #userList {
            width: 300px;
            background: white;
            border-right: 1px solid #ddd;
            overflow-y: auto;
        }
        #userList h3 {
            background: #8e44ad;
            color: white;
            padding: 15px;
            margin: 0;
        }
        .user {
            padding: 12px 20px;
            border-bottom: 1px solid #f0f0f0;
            cursor: pointer;
            display: flex;
            align-items: center;
            transition: background 0.3s;
        }
        .user:hover {
            background: #f8f8f8;
        }
        .user img {
            border-radius: 50%;
            width: 40px;
            height: 40px;
            object-fit: cover;
            margin-right: 10px;
        }
        #chatContainer {
            flex: 1;
            padding: 20px;
            display: flex;
            flex-direction: column;
            background: white;
        }
        #chatHeader {
            font-weight: bold;
            margin-bottom: 10px;
        }
        #chatMessages {
            flex: 1;
            overflow-y: auto;
            background: #e9ebee;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 10px;
        }
        .message {
            max-width: 70%;
            padding: 10px 15px;
            margin-bottom: 12px;
            border-radius: 20px;
            font-size: 15px;
            line-height: 1.4;
            clear: both;
        }
        .patient {
            background: #d4edda;
            float: right;
            border-bottom-left-radius: 0;
        }
        .doctor {
            background: #8e44ad;
            color: white;
            float: left;
            border-bottom-right-radius: 0;
        }
        #messageInput {
            padding: 10px;
            width: 100%;
            border: 1px solid #ccc;
            border-radius: 25px;
            margin-bottom: 10px;
        }
        #sendButton {
            background: #8e44ad;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 25px;
            cursor: pointer;
            align-self: flex-end;
        }
        #sendButton:disabled {
            background: #ccc;
        }
    </style>
</head>
<body>
<div class="chat-layout">
    <div id="userList">
        <h3>Bác Sĩ</h3>
        <?php
        include_once("Controllers/ctaikhoan.php");
        $p = new ctaiKhoan();
        $tentk1=$_SESSION['user']['tentk'];
        $tbl = $p->gettkbacsi($tentk1);

        if ($tbl && $tbl->num_rows > 0) {
            while ($row = $tbl->fetch_assoc()) {
                echo "<div class='user' onclick='selectUser(\"{$row['tentk']}\", \"{$row['hoten']}\")'>
                        <img src='Assets/img/{$row['imgbs']}' alt='BS'>
                        <span>{$row['hoten']}</span>
                      </div>";
            }
        } else {
            echo "<p class='p-3'>Không có bác sĩ nào.</p>";
        }
        ?>

    </div>
    <div id="chatContainer">
        <div id="chatHeader">Chọn bác sĩ để trò chuyện</div>
        <div id="chatMessages"></div>
        <textarea id="messageInput" placeholder="Nhập tin nhắn..." disabled></textarea>
        <button id="sendButton" disabled>Gửi</button>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
let socket;
let user = { tentk: "<?php echo htmlspecialchars($tentk, ENT_QUOTES, 'UTF-8'); ?>", vaitro: 1 };
let currentDoctor = null;
let messages = {}; // Store messages by doctor ID

// Kết nối WebSocket
function connectWebSocket() {
    socket = new WebSocket('ws://localhost:8080');
    socket.onopen = () => {
        console.log("WebSocket connected!");
        socket.send(JSON.stringify({ command: 'register', username: user.tentk, role: user.vaitro }));
    };
        socket.onmessage = (event) => {
            const data = JSON.parse(event.data);
            if (data.command === 'messages') {
                const docID = data.receiver_tentk;
                messages[docID] = data.messages;
                if (currentDoctor && currentDoctor.tentk === docID) {
                    renderMessages(messages[docID]);
                }
            } else if (data.command === 'receive') {
                if (!messages[data.sender]) messages[data.sender] = [];
                messages[data.sender].push(data);
                if (currentDoctor && currentDoctor.tentk === data.sender) {
                    displayMessage(data);
                }
            }
        };
    socket.onclose = () => {
        console.warn("WebSocket closed. Attempting to reconnect...");
        setTimeout(connectWebSocket, 3000); // Try to reconnect after 3 seconds
    };
}

// Chọn bác sĩ và tải tin nhắn
function selectUser(tentk, name) {
    currentDoctor = { tentk, name };
    $('#chatHeader').text('Bạn đang trò chuyện với bác sĩ ' + name);
    $('#messageInput').prop('disabled', false);
    $('#sendButton').prop('disabled', false);
    $('#chatMessages').html(''); // Clear chat window before loading new messages

    // Kiểm tra nếu đã có tin nhắn cho bác sĩ này trong mảng messages
    if (!messages[tentk]) messages[tentk] = [];

    renderMessages(messages[tentk]);

    // Yêu cầu lịch sử tin nhắn từ server
    if (socket && socket.readyState === WebSocket.OPEN) {
        socket.send(JSON.stringify({
            command: "load_messages",
            tentk: user.tentk,
            receiver_tentk: tentk
        }));
    } else {
        console.warn("WebSocket is not ready. Retrying...");
        setTimeout(() => selectUser(tentk, name), 2000); // Retry if WebSocket is not ready
    }
}


// Render tin nhắn lên giao diện
function renderMessages(msgArray) {
    $('#chatMessages').html(''); // Clear the chat window before rendering new messages
    msgArray.forEach(m => displayMessage(m));
}

// Hiển thị từng tin nhắn
function displayMessage(msg) {
    const msgDiv = $('<div class="message"></div>');
    msgDiv.text(msg.message);
    msgDiv.addClass(msg.sender === user.tentk ? 'patient' : 'doctor'); // Apply different classes based on sender
    $('#chatMessages').append(msgDiv);
    $('#chatMessages').scrollTop($('#chatMessages')[0].scrollHeight); // Auto-scroll to the bottom
}

// Gửi tin nhắn
$('#sendButton').click(() => {
    const text = $('#messageInput').val().trim();
    if (!text || !currentDoctor) return;
    console.log("Dữ liệu gửi đi:", {
        bs: currentDoctor.tentk,
        bn: user.tentk
    });
    // Gửi AJAX kiểm tra giờ hẹn
        $.ajax({
        url: '/hanhphuchospital3/Ajax/getlichhen.php',
        type: 'POST',
        dataType: 'json', 
        data: {
            bs: currentDoctor.tentk,
            bn: user.tentk
        },
        success: function(response) {
            console.log(response); // In ra để kiểm tra phản hồi từ server
            if (response.status === 'ok') {
                // Được phép gửi tin nhắn
                const msg = {
                    command: 'send',
                    sender: user.tentk,
                    receiver: currentDoctor.tentk,
                    message: text
                };

                if (socket && socket.readyState === WebSocket.OPEN) {
                    socket.send(JSON.stringify(msg));
                    if (!messages[currentDoctor.tentk]) messages[currentDoctor.tentk] = [];
                    messages[currentDoctor.tentk].push(msg);
                    displayMessage(msg);
                    $('#messageInput').val('');
                } else {
                    alert("WebSocket chưa sẵn sàng.");
                }
            } else {
                alert(response.message); // Thông báo lỗi nếu chưa đến giờ hẹn
            }
        },
        error: function(xhr, status, error) {
            console.log(xhr.responseText); // In lỗi từ server để debug
            alert("Không thể kiểm tra lịch hẹn. Vui lòng thử lại.");
        }
    });

});


// Kết nối WebSocket
connectWebSocket();


</script>
</body>
</html>
