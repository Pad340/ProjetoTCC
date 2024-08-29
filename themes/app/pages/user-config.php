<div class="user-config">
    <h1>Configurações da conta</h1>

    <?php
    if (!$session->has('authSeller')) {
        ?>
        <div class="seller-form">
            <h2>Criar uma conta de vendedor</h2>
            <form action method="post" autocomplete="off">
                <label for="seller_name">Nome de vendedor ou turma:</label>
                <input type="text" name="seller_name" id="seller_name" required/>

                <label for="seller_cpf">CPF do vendedor ou responsável pelas vendas da turma:</label>
                <input type="text" name="seller_cpf" id="seller_cpf" maxlength="14" required/>

                <label for="phone_number">Número de telefone do vendedor ou responsável pelas vendas da turma:</label>
                <input type="text" name="phone_number" id="phone_number" maxlength="15" required/>

                <button type="submit" name="seller_btn">Cadastrar</button>
            </form>
        </div>
        <?php
    }
    ?>
    <script>
        $(document).ready(function () {
            // Máscara para CPF
            $('#seller_cpf').mask('000.000.000-00');

            // Máscara para o número de telefone
            const phoneMaskBehavior = function (val) {
                    return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
                },
                phoneOptions = {
                    onKeyPress: function (val, e, field, options) {
                        field.mask(phoneMaskBehavior.apply({}, arguments), options);
                    }
                };

            $('#phone_number').mask(phoneMaskBehavior, phoneOptions);
        });
    </script>
</div>