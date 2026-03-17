<script>
    const chatButton = document.getElementById("chatButton");
    const chatBox = document.getElementById("chatBox");
    const closeChat = document.getElementById("closeChat");

    chatButton.addEventListener("click", () => {
        chatBox.style.display = "flex";
        chatButton.style.display = "none";
    });

    closeChat.addEventListener("click", () => {
        chatBox.style.display = "none";
        chatButton.style.display = "flex";
    });

    $('.myFunctionWACs').click(function() {
        var slugWa = $(this).data('url');
        window.location = "https://api.whatsapp.com/send?phone=6282180744966&text=" + slugWa;
    });

    $('.myFunctionWATs').click(function() {
        var slugWa = $(this).data('url');
        window.location = "https://api.whatsapp.com/send?phone=6281532423436&text=" + slugWa;
    });
</script>