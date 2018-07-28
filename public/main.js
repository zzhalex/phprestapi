function updateUser(user){
    console.log(user);
    $('#aid').val(user.id);
    $('#title').val(user.title);
    $('#description').val(user.description);

    $('#update').show();
    $('#submit').hide();
}


