</div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe"
        crossorigin="anonymous"></script>
</body>
</html>
<script>
const hamBurger = document.querySelector(".toggle-btn");

hamBurger.addEventListener("click", function () {
  document.querySelector("#sidebar").classList.toggle("expand");
});

function disableButtonOnMobile() {
    const button = document.querySelector('.toggle-btn');
    if (window.innerWidth <= 768) {
        button.disabled = true; // Desativa o botão em dispositivos móveis
    } else {
        button.disabled = false; // Reativa o botão em telas maiores
    }
}

// Chama a função no carregamento da página e quando a janela é redimensionada
window.addEventListener('load', disableButtonOnMobile);
window.addEventListener('resize', disableButtonOnMobile);

</script>
