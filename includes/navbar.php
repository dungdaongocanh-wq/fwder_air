<?php
// Navbar dùng chung cho toàn bộ hệ thống
if (session_status() === PHP_SESSION_NONE) session_start();
$current_page = basename($_SERVER['PHP_SELF']);
$current_dir  = basename(dirname($_SERVER['PHP_SELF']));

function isActiveNav($page_dirs = [], $page_files = []) {
    global $current_page, $current_dir;
    if (in_array($current_dir, $page_dirs)) return 'active';
    if (in_array($current_page, $page_files)) return 'active';
    return '';
}

$depth = (dirname($_SERVER['PHP_SELF']) === '/' || dirname($_SERVER['PHP_SELF']) === '\\')
       ? '' : '../';
$base = BASE_URL . '/';
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary sticky-top">
    <div class="container-fluid px-2 px-md-3">

        <!-- Brand -->
        <a class="navbar-brand fw-bold fs-6" href="<?php echo $base; ?>index.php">
            <i class="bi bi-airplane-fill me-1"></i>
            <span class="d-none d-sm-inline">KEN LOGISTICS</span>
            <span class="d-inline d-sm-none">KEN</span>
        </a>

        <!-- Mobile: hiển thị tên user + toggler -->
        <div class="d-flex align-items-center d-lg-none ms-auto gap-2">
            <span class="text-white small">
                <i class="bi bi-person-circle me-1"></i>
                <?php echo htmlspecialchars(explode(' ', $_SESSION['full_name'])[0]); ?>
            </span>
            <button class="navbar-toggler border-0 p-1" type="button"
                    data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">

                <!-- Dashboard -->
                <li class="nav-item">
                    <a class="nav-link <?php echo isActiveNav([], ['index.php','dashboard.php']); ?>"
                       href="<?php echo $base; ?>index.php">
                        <i class="bi bi-speedometer2"></i>
                        <span class="ms-1">Dashboard</span>
                    </a>
                </li>

                <?php if (isManager()): ?>
                <!-- Danh mục -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle <?php echo isActiveNav(['customers','shippers','consignees','airlines','airports']); ?>"
                       href="#" data-bs-toggle="dropdown">
                        <i class="bi bi-database"></i>
                        <span class="ms-1">Danh mục</span>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="<?php echo $base; ?>customers/index.php">
                            <i class="bi bi-building text-info me-2"></i>Khách hàng
                        </a></li>
                        <li><a class="dropdown-item" href="<?php echo $base; ?>shippers/index.php">
                            <i class="bi bi-box-arrow-up text-success me-2"></i>Shipper
                        </a></li>
                        <li><a class="dropdown-item" href="<?php echo $base; ?>consignees/index.php">
                            <i class="bi bi-box-arrow-down text-warning me-2"></i>Consignee
                        </a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="<?php echo $base; ?>airlines/index.php">
                            <i class="bi bi-airplane text-primary me-2"></i>Hãng bay
                        </a></li>
                        <li><a class="dropdown-item" href="<?php echo $base; ?>airports/index.php">
                            <i class="bi bi-geo-alt text-danger me-2"></i>Sân bay
                        </a></li>
                    </ul>
                </li>

                <!-- CW Rules -->
                <li class="nav-item">
                    <a class="nav-link <?php echo isActiveNav(['cw_rules']); ?>"
                       href="<?php echo $base; ?>cw_rules/index.php">
                        <i class="bi bi-calculator"></i>
                        <span class="ms-1">CW Rules</span>
                    </a>
                </li>
                <?php endif; ?>

                <!-- MAWB -->
                <li class="nav-item">
                    <a class="nav-link <?php echo isActiveNav(['mawb']); ?>"
                       href="<?php echo $base; ?>mawb/index.php">
                        <i class="bi bi-file-earmark-text"></i>
                        <span class="ms-1">Vận đơn MAWB</span>
                    </a>
                </li>

                <!-- Tra cứu Cont -->
                <li class="nav-item">
                    <a class="nav-link <?php echo isActiveNav(['tracuu_cont']); ?>"
                       href="<?php echo $base; ?>tracuu_cont/index.php">
                        <i class="bi bi-search"></i>
                        <span class="ms-1">IN Mã Vạch Tờ Khai</span>
                    </a>
                </li>

                <!-- Danh sách hàng hoá -->
                <li class="nav-item">
                    <a class="nav-link <?php echo isActiveNav(['danh_sach_hang']); ?>"
                       href="<?php echo $base; ?>danh_sach_hang/index.php">
                        <i class="bi bi-list-columns-reverse"></i>
                        <span class="ms-1">Lấy danh sách hàng Tờ Khai</span>
                    </a>
                </li>

                <!-- ===== IN LABEL HÀNG KHÔNG ===== -->
                <li class="nav-item">
                    <a class="nav-link <?php echo isActiveNav(['in_label_air']); ?>"
                       href="<?php echo $base; ?>in_label_air/index.php">
                        <i class="bi bi-tag-fill"></i>
                        <span class="ms-1">In Label Hàng Không</span>
                    </a>
                </li>

                <?php if (isAdmin()): ?>
                <!-- Quản trị -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle <?php echo isActiveNav(['accounts','settings']); ?>"
                       href="#" data-bs-toggle="dropdown">
                        <i class="bi bi-gear"></i>
                        <span class="ms-1">Quản trị</span>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="<?php echo $base; ?>accounts/index.php">
                            <i class="bi bi-people me-2"></i>Tài khoản
                        </a></li>
                        <li><a class="dropdown-item" href="<?php echo $base; ?>settings/index.php">
                            <i class="bi bi-sliders me-2"></i>Cài đặt hệ thống
                        </a></li>
                    </ul>
                </li>
                <?php endif; ?>

            </ul>

            <!-- User menu (desktop) -->
            <ul class="navbar-nav d-none d-lg-flex">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                        <i class="bi bi-person-circle me-1"></i>
                        <?php echo htmlspecialchars($_SESSION['full_name']); ?>
                        <span class="badge ms-1
                            <?php echo $_SESSION['role'] === 'admin'   ? 'bg-danger' :
                                      ($_SESSION['role'] === 'manager' ? 'bg-warning text-dark' : 'bg-secondary'); ?>">
                            <?php echo ucfirst($_SESSION['role']); ?>
                        </span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="<?php echo $base; ?>accounts/profile.php">
                            <i class="bi bi-person-gear me-2"></i>Hồ sơ cá nhân
                        </a></li>
                        <li><a class="dropdown-item" href="/fwder_air/includes/change_password.php">
                            <i class="bi bi-key me-2 text-warning"></i>Đổi mật khẩu
                        </a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="<?php echo $base; ?>logout.php">
                            <i class="bi bi-box-arrow-right me-2"></i>Đăng xuất
                        </a></li>
                    </ul>
                </li>
            </ul>

            <!-- User menu (mobile) -->
            <ul class="navbar-nav d-lg-none border-top border-primary mt-2 pt-2">
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo $base; ?>accounts/profile.php">
                        <i class="bi bi-person-gear me-2"></i>Hồ sơ cá nhân
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/fwder_air/includes/change_password.php">
                        <i class="bi bi-key me-2 text-warning"></i>Đổi mật khẩu
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-danger" href="<?php echo $base; ?>logout.php">
                        <i class="bi bi-box-arrow-right me-2"></i>Đăng xuất
                    </a>
                </li>
            </ul>

        </div>
    </div>
<?php if (!defined('MOBILE_CSS_LOADED')): define('MOBILE_CSS_LOADED', true); ?>
<link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/mobile.css">
<?php endif; ?>
</nav>
