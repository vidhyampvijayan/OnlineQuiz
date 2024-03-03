<!DOCTYPE html>
<html lang="en" dir="ltr">
   <head>
      <meta charset="utf-8">
      <title>Online Quiz - Signup</title>
      <link rel="stylesheet" href="{{ asset('css/style.css') }}">
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"/>
   </head>
   <body>
      <div class="container">
         <header>Online Quiz - Signup</header>
         <form method="POST" action="{{ route('register') }}">
            @csrf
            <div class="input-field">
            <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus>
               <label>Username</label>
            </div>
            <div class="input-field">
            <input id="email" type="email" name="email" value="{{ old('email') }}" required>      
               <label>Email</label>
            </div>

            <div class="input-field">
            <input id="password" type="password" name="password" required>
               <label>Password</label>
            </div>
            <div class="input-field">
            <input id="password_confirmation" type="password" name="password_confirmation" required>
               <label>Confirm Password</label>
            </div>


            <div class="button">
               <div class="inner"></div>
               <button>Register</button>
            </div>
         </form>
       
       
         <div class="signup">
          <a href="{{ route('login') }}">Login</a>
         </div>
      </div>
      <script>
         var input = document.querySelector('.pswrd');
         show.addEventListener('click', active);
         function active(){
           if(input.type === "password"){
             input.type = "text";
             show.style.color = "#1DA1F2";
             show.textContent = "HIDE";
           }else{
             input.type = "password";
             show.style.color = "#111";
           }
         }
      </script>
   </body>
</html>