<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __("Forum Hub") }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <!-- my area begin -->

                <head>
                    <meta charset="utf-8" />
                    <meta name="csrf-token" content="{{ csrf_token() }}" />
                    <meta
                        name="viewport"
                        content="width=device-width, initial-scale=1"
                    />
                    <!-- Bootstrap CSS -->
                    <link
                        href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css"
                        rel="stylesheet"
                        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3"
                        crossorigin="anonymous"
                    />
                </head>

                <body>
                    <div class="container-fluid">
                        <form id="createThread" class="col-sm">
                            <div class="mb-2">
                                <label class="form-label">スレッド名</label>
                                <input
                                    class="form-control"
                                    type="text"
                                    name="threadName"
                                />
                            </div>
                            <button
                                id="create_threadBtn"
                                class="btn btn-primary"
                            >
                                {{ __("Create new thread") }}
                            </button>
                        </form>

                        <br /><br />

                        @if (Auth::user()->is_admin)
                        <form id="thread_actions_form">
                            <label class="form-label">対象：スレッドID</label>
                            <input
                                id="thread_id"
                                class="form-control"
                                type="text"
                            />
                            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                                <li class="nav-item dropdown">
                                    <a
                                        class="nav-link dropdown-toggle"
                                        href="#"
                                        id="navbarDropdown"
                                        role="button"
                                        data-bs-toggle="dropdown"
                                        aria-expanded="false"
                                    >
                                        操作
                                    </a>
                                    <ul
                                        class="dropdown-menu"
                                        aria-labelledby="navbarDropdown"
                                    >
                                        <li>
                                            <!-- actions -->
                                            <button
                                                type="button"
                                                class="dropdown-item"
                                                data-bs-toggle="modal"
                                                data-bs-target="#DeleteThreadModal"
                                            >
                                                {{ __("Delete") }}
                                            </button>
                                            <button
                                                type="button"
                                                class="dropdown-item"
                                                data-bs-toggle="modal"
                                                data-bs-target="#EditThreadModal"
                                            >
                                                {{ __("Edit") }}
                                            </button>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </form>
                        @endif

                        <br /><br />

                        <div>
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>{{ __("Thread name") }}</th>
                                        <td>{{ __("Create time") }}</td>
                                        @if (Auth::user()->is_admin)
                                        <td>
                                            {{ __("Thread ID") }}
                                        </td>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($tables as $tableInfo)
                                    <?php
										$tableName = str_replace('/', '&slash;', $tableInfo['thread_name']);
										$tableName = str_replace('\\', '&backSlash;' , $tableName);
										$tableName = str_replace('#', '&hash;', $tableName);
									?>
                                    <tr>
                                        <th>
                                            <a
                                                href="hub/thread_name={{
                                                    $tableName
                                                }}/id={{
                                                    $tableInfo['thread_id']
                                                }}"
                                                class="text-decoration-none"
                                                >{{
                                                    $tableInfo["thread_name"]
                                                }}</a
                                            >
                                        </th>
                                        <td>
                                            {{ $tableInfo["created_at"] }}
                                        </td>
                                        @if (Auth::user()->is_admin)
                                        <td>
                                            {{ $tableInfo["thread_id"] }}
                                        </td>
                                        @endif
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Modal -->
                    <div
                        class="modal fade"
                        id="DeleteThreadModal"
                        tabindex="-1"
                        aria-labelledby="DeleteThreadModalLabel"
                        aria-hidden="true"
                    >
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-body">
                                    {{ __("Do you really want to delete it?") }}
                                </div>
                                <div class="modal-footer">
                                    <button
                                        type="button"
                                        class="btn btn-secondary"
                                        data-bs-dismiss="modal"
                                    >
                                        Close
                                    </button>
                                    <button
                                        id="delete_threadBtn"
                                        type="button"
                                        class="btn btn-primary"
                                        data-bs-dismiss="modal"
                                    >
                                        Save changes
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div
                        class="modal fade"
                        id="EditThreadModal"
                        tabindex="-1"
                        aria-labelledby="EditThreadModalLabel"
                        aria-hidden="true"
                    >
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    {{ __("Edit thread") }}
                                </div>
                                <div class="modal-body">
                                    <form id="edit_thread_form">
                                        <div class="mb-3">
                                            <label
                                                for="thread-name"
                                                class="col-form-label"
                                                >{{ __("Thread name") }}</label
                                            >
                                            <input
                                                id="ThreadNameText"
                                                type="text"
                                                class="form-control"
                                            />
                                        </div>
                                    </form>
                                </div>
                                <div class="modal-footer">
                                    <button
                                        type="button"
                                        class="btn btn-secondary"
                                        data-bs-dismiss="modal"
                                    >
                                        Close
                                    </button>
                                    <button
                                        id="edit_threadBtn"
                                        type="button"
                                        class="btn btn-primary"
                                        data-bs-dismiss="modal"
                                    >
                                        Save changes
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <!-- Bootstrap用JavaScript -->
                        <script
                            src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
                            integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p"
                            crossorigin="anonymous"
                        ></script>

                        <!-- jQuery -->
                        <script src="https://code.jquery.com/jquery-3.0.0.min.js"></script>

                        <!-- グローバル変数 -->
                        <script>
                            const url = "{{ $url }}";
                        </script>

                        <!-- others -->
                        <script src="{{ mix('js/app_jquery.js') }}"></script>
                    </div>
                </body>
                <!-- my area end -->
            </div>
        </div>
    </div>
</x-app-layout>
