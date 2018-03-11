$.ajax({ 
    type: 'GET',
    dataType: 'json',
    url: 'http://localhost/git/phpoo/rest.php', 
    data: { 
        'class' : 'PessoaServices', 
        'method': 'getData', 
        'id'    : '1'
    }, 
    success: function (response) { 
        console.log(response.data);
    }
});