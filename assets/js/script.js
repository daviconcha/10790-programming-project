// Função para exibir os links gerados
function displayLinks(shortLink, statusLink) {
    const linkContainer = document.getElementById("linkContainer");

    // Criando o conteúdo explicativo
    const heading = document.createElement("h3");
    heading.textContent = "Your link is ready!";

    const description = document.createElement("p");
    description.textContent = "This is the link to share with your target audience. Below, click on the 'Link Details' button for statistics and other details.";

    // Criando o input para o link compartilhado com a URL completa
    const input = document.createElement("input");
    input.type = "text";
    input.className = "sharing-link-input";
    //input.value = "https://localhost:81/linkSnap/" + shortLink; // Adiciona a URL completa
    input.value = window.location.origin + "/linksnap/" + shortLink;
    input.setAttribute("readonly", true);

    // Criando a div de aviso para o "copiado para área de transferência"
    const copyNotification = document.createElement("div");
    copyNotification.className = "copy-notification";
    copyNotification.textContent = "Link copied to clipboard!";  // Mensagem de aviso

    // Adicionando o evento de clique para copiar para a área de transferência
    input.addEventListener("click", function() {
        input.select();
        input.setSelectionRange(0, 99999); // Para dispositivos móveis
        document.execCommand("copy");

        // Exibe a div de aviso
        document.body.appendChild(copyNotification);
        setTimeout(function() {
            copyNotification.remove();
        }, 3000); // 3000 milissegundos (3 segundos)
    });

    // Criando o link para visualização dos detalhes
    const statusLinkElement = document.createElement("a");
    statusLinkElement.href = statusLink;
    statusLinkElement.target = "_blank";
    statusLinkElement.textContent = "Link Details";
    statusLinkElement.classList.add("details-link");

    // Limpando o conteúdo anterior e adicionando os novos elementos
    linkContainer.innerHTML = ''; // Limpa qualquer conteúdo anterior
    linkContainer.appendChild(heading);
    linkContainer.appendChild(description);
    linkContainer.appendChild(input);
    linkContainer.appendChild(statusLinkElement);

    // Exibe o linkContainer apenas quando há links gerados
    linkContainer.style.display = 'block';
}

// Interceptar o envio do formulário para exibir os links abaixo do formulário
document.getElementById("shortenerForm").addEventListener("submit", async function(event) {
    event.preventDefault(); // Impede o envio padrão do formulário

    const formData = new FormData(this);
    const response = await fetch("create_link.php", {
        method: "POST",
        body: formData
    });

    const result = await response.json(); // Recebe o link encurtado em formato JSON
    if (result.success) {
        displayLinks(result.shortLink, result.statusLink);
    } else {
        alert("Erro ao encurtar o link. Tente novamente.");
    }
});
