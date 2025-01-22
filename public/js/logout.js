function logout() {
    var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    fetch('/' + empresa + '/logout', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken // Adiciona o CSRF token no cabeçalho
        }
    })
    .then(response => {
        if (response.ok) {
            window.location.href = '/' + empresa + '/login'; // Redireciona para a página de login
        } else {
            alert('Erro ao tentar deslogar. Tente novamente.');
        }
    })
    .catch(error => {
        alert('Erro ao tentar deslogar. Tente novamente.');
    });
}

