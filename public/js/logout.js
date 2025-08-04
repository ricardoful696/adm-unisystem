function logout() {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    fetch('/' + empresa + '/logout', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        }
    })
    .then(response => {
        if (response.ok) {
            window.location.replace('/' + empresa + '/login');
        } else {
            alert('Erro ao tentar deslogar. Tente novamente.');
        }
    })
    .catch(error => {
        alert('Erro ao tentar deslogar. Tente novamente.');
    });
}
