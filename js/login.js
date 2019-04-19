  function onSuccess(googleUser) {
  document.getElementById('picture').innerHTML = '<img src= "' + googleUser.getBasicProfile().getImageUrl() + '"class="w3-circle" " style="height:106px;width:106px" alt="Avatar">';
  document.getElementById('name').innerHTML = googleUser.getBasicProfile().getName();
  document.getElementById('email').innerHTML = googleUser.getBasicProfile().getEmail();
  document.getElementById('avatar').innerHTML = '<img src= "' + googleUser.getBasicProfile().getImageUrl() + '" class="w3-circle" style="height:25px;width:25px" alt="Avatar">';
  var Name = "Name=" + googleUser.getBasicProfile().getName();document.cookie = Name;
  var Email = "Email=" + googleUser.getBasicProfile().getEmail();document.cookie = Email;
  var Avatar = "Avatar=" + googleUser.getBasicProfile().getImageUrl();document.cookie = Avatar;
  }

  function onFailure(error) {
    console.log(error);
  }

  function renderButton() {
    gapi.signin2.render('my-signin2', {
      'scope': 'profile email',
      'width': 130,
      'height': 26,
      'longtitle': false,
      'theme': 'dark',
      'onsuccess': onSuccess,
      'onfailure': onFailure
    });
  }

  function signOut() {
  var auth2 = gapi.auth2.getAuthInstance();
  auth2.signOut().then(function () {
    console.log('User signed out.');
        document.getElementById('picture').innerHTML = "";
        document.getElementById('name').innerHTML = "";
        document.getElementById('email').innerHTML = "";
        document.getElementById('avatar').innerHTML = "";
  var Name = "Name=";document.cookie = Name;
  var Email = "Email=";document.cookie = Email;
  var Avatar = "Avatar=";document.cookie = Avatar;
  });
}
