<div>
    <div class="card flex" style="
        min-height: 50vh;
        max-width: max-content;
        padding: 50px;
        background-color: #734aa7;
        font-family: 'Roboto';
        color: azure;
        ">
        <div class="card-item">
            <h3 style="color: white;">Hi, <?= $username ?? 'User' ?> </h3>
            <span style="color:#98e6ff;">Click the button to reset your password:</span>
            <br>
            <br>
            <br>
            <a class="button" style="background-color:#005571; color:white; padding: 15px 25px; text-decoration:none;font-weight:900;box-shadow: 10px 9px 5px 0px rgba(0,0,0,0.75);-webkit-box-shadow: 10px 9px 5px 0px rgba(0,0,0,0.75);-moz-box-shadow: 10px 9px 5px 0px rgba(0,0,0,0.75);" href=" <?= base_url('reset-password') . '/' . $token ?> ">Reset Password</a>

            <br><br><br>
            <span style="color: white;">or click the link below and use the token</span>
            <br>
            <a href=" <?= base_url('reset-password') . '/' . $uuid ?> " style="color:#98e6ff; font-weight: 600;">Reset Password</a>
            <br>
            <br>
            <span style="color: white;">Token:</span>
            <h3 style="color:#98e6ff"> <?= $token ?? '981230' ?> </h3>
        </div>
    </div>
</div>