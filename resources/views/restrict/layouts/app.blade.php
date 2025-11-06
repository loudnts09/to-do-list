<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <title>@yield('title', 'Painel') - To-Do List</title> 
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        
        <style>
            body {
                background-color: #f4f7fa;
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            }

            .card {
                border: none;
                border-radius: 16px;
                box-shadow: 0 8px 20px rgba(0, 0, 0, 0.05);
            }

            .card-header {
                background: #fdfdff;
                font-weight: 600;
                font-size: 1.1rem;
                border-bottom: 1px solid #e6e8eb;
            }

            .form-control, .form-select {
                border-radius: 8px;
            }

            table th {
                background-color: #f5f6f9;
                color: #333;
            }

            .table-striped > tbody > tr:nth-of-type(odd) {
                background-color: #fcfcfd;
            }

            .table td, .table th {
                vertical-align: middle;
            }

            footer {
                font-size: 0.9rem;
            }
            .dropdown-toggle::after {
                margin-left: 0.5rem;
            }
            
            .btn-outline-light:hover {
                background-color: rgba(255, 255, 255, 0.1);
                border-color: rgba(255, 255, 255, 0.5);
            }
            
            .dropdown-item:hover {
                background-color: #f8f9fa;
            }
            
            .dropdown-item.text-danger:hover {
                background-color: #f8d7da;
                color: #721c24 !important;
            }

            .pagination {
                justify-content: center;
            }
            .page-item.active .page-link {
                background-color: #343a40;
                border-color: #343a40;
            }
            .page-link {
                color: #343a40;
            }
            .page-link:hover {
                color: #000;
            }
            .page-item.disabled .page-link {
                color: #6c757d;
            }
        </style>
    </head>
    <body class="d-flex flex-column min-vh-100">
        <header class="py-4 shadow-sm bg-dark">
            <div class="container">
                <div class="d-flex justify-content-between align-items-center">
                    <h1 class="m-0" style="color: #fff;">To-Do List</h1>
                    
                    <div class="dropdown">
                        <button class="btn btn-outline-light dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            👤 {{ auth()->user()->name ?? 'Usuário' }}
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">
                                        🚪 Sair
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </header>

        <main class="flex-fill py-5">
            <div class="container">
                
                @yield('content')

            </div>
        </main>

        <footer class="py-3 mt-auto text-center bg-dark">
            <div class="container">
                <span style="color: #fff;">© {{ date('Y') }} - To-Do List</span>
            </div>
        </footer>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <script>
            const deleteModal = document.getElementById('deleteModal');
            if (deleteModal) {
                deleteModal.addEventListener('show.bs.modal', event => {
                    const button = event.relatedTarget;
                    const actionUrl = button.getAttribute('data-action');
                    const deleteForm = deleteModal.querySelector('#deleteForm');
                    deleteForm.setAttribute('action', actionUrl);
                });
            }
        </script>
    </body>
</html>