<div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-/bQdsTh/da6pkI1MST/rWKFNjaCP5gBSY4sEBT38Q/9RBh9AH40zEOg7Hlq2THRZ"
        crossorigin="anonymous"></script>
    <script
        src="https://cdnjs.cloudflare.com/ajax/libs/iframe-resizer/3.5.14/iframeResizer.contentWindow.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script type="text/javascript" src="https://cdn.senior.com.br/platform/1.7.1/js/workflow-cockpit.min.js"></script>
    <script type="text/javascript" src="https://cdn.senior.com.br/bpm/1.10.0/workflow-cockpit.min.js"></script>

    <script>

        this.workflowCockpit = workflowCockpit({
        init: _init,
        onSubmit: _saveData,
        onError: _rollback,
        });

        function _init(data, info) {
        console.log("Função _init chamada. Dados iniciais do formulário:", data);

        // Exemplo: Preencher campos com dados iniciais, se disponíveis
        if (data && data.loadContext && data.loadContext.initialVariables) {
            const { initialVariables } = data.loadContext;
            document.getElementById("campoExemplo").value = initialVariables.exemplo || 'Não disponível';
        }
        }

        function _rollback(error) {
        console.error('Erro no workflow. Rollback necessário:', error);
        // Implemente aqui a lógica de rollback necessária, se aplicável
        }

        async function _saveData(newData) {
        // Constrói o objeto de dados a serem salvos
        let newData = {
            cnpj: "teste"
        };

        // Exibe os dados no console para verificação
        console.log("Dados a serem salvos:", newData);

        // Aqui você pode implementar a lógica de envio para o serviço SOAP ou outra integração necessária

        // Retorna os dados para serem enviados ao workflow
        return {
            formData: newData,
        };
        }
    </script>
    

    <title>{{ $title }}</title>
</div>