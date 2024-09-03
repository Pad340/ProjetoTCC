<header>
    <a href="<?= url('app/home') ?>">
        <img src="" alt="website-logo" width="20">
    </a>
    <nav>
        <ul>
            <li><a href="<?= url('app/home') ?>">Home</a></li>
            <li><a href="<?= url('app/products') ?>">Seus produtos</a></li>
            <li><a href="<?= url('app/cart') ?>">Carrinho</a></li>
            <li>Conta
                <ul>
                    <li><a href="<?= url('app/user-config') ?>">Configurar</a></li>
                    <li><a href="<?= url('app/logout') ?>">Logout</a></li>
                </ul>
            </li>
        </ul>
    </nav>
</header>