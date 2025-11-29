document.addEventListener('DOMContentLoaded', () => {
    // --- LÓGICA PARA MOSTRAR EL NOMBRE DE USUARIO ---
    const usernameElement = document.querySelector('.username');
    const storedName = localStorage.getItem('userName');
    const ticketcontaner = document.querySelector('.tickets');

    if (usernameElement) {
        if (storedName) {
            // Reemplaza el texto (####) por el nombre real
            usernameElement.textContent = storedName;
        } else {
            // Fallback por si no hay nombre guardado
            usernameElement.textContent = "Usuario Anónimo";
        }
    }

    // --- LÓGICA DE LOGOUT (Ya existente) ---
    const logoutButton1 = document.querySelector('.logout');
    if (logoutButton1) {
        logoutButton1.addEventListener('click', async () => {
            const token = localStorage.getItem('authToken');
            
            if (token) {
                try {
                    await fetch('http://127.0.0.1:8001/view/logout', {
                        method: 'GET',
                        headers: {
                            'Authorization': token,
                            'Content-Type': 'application/json'
                        }
                    });
                } catch (error) {
                    console.error("Error al intentar logout:", error);
                }
            }
            
            localStorage.clear();
            window.location.href = '../index.html'; 
        });
    }
    // 1. Referencia al botón de Logout
    const logoutButton = document.querySelector('.logout');

    if (logoutButton) {
        logoutButton.addEventListener('click', async () => {
            
            // 2. Recuperar el token actual para enviarlo en la cabecera
            const token = localStorage.getItem('authToken');

            // Si hay token, intentamos cerrar sesión en el servidor
            if (token) {
                try {
                    // Consumo del Endpoint Logout definido en usermicroservice.http
                    const response = await fetch('http://127.0.0.1:8001/view/logout', {
                        method: 'GET',
                        headers: {
                            'Authorization': token, // Enviamos el token actual
                            'Content-Type': 'application/json'
                        }
                    });

                    if (response.ok) {
                        console.log("Sesión cerrada exitosamente en el servidor.");
                    } else {
                        console.warn("El servidor reportó un error, pero se procederá con el logout local.");
                    }

                } catch (error) {
                    console.error("Error de conexión al intentar logout:", error);
                }
            }

            // 3. Limpieza total de datos locales (Token, Roles, etc.)
            // Esto cumple con tu requerimiento de "eliminar el resto de información"
            localStorage.clear();

            // 4. Redirección al index (Login)
            // Ajusta la ruta "../index.html" según la estructura de tus carpetas. 
            // Si dashboard está en /views/user/, el index está dos niveles arriba o en la raíz.
            window.location.href = '../index.html'; 
        });
    }

    // --- LÓGICA PARA MOSTRAR TICKETS ASIGNADOS AL USUARIO ---
    
    queryAllUser();

    // ---------------------------------------------------
    // Modal: abrir/mostrar solo al dar click en open-modal-btn
    // ---------------------------------------------------
    const openModalBtn = document.getElementById('open-modal-btn');
    const modalOverlay = document.querySelector('.modal-overlay');
    const modalContent = document.querySelector('.modal-content');
    const closeButton = document.querySelector('.close-button');
    const cancelButton = document.querySelector('.cancel-btn');

    // Asegurar ocultar por defecto
    if (modalOverlay) modalOverlay.classList.remove('show');

    const closeModal = () => {
        if (!modalOverlay) return;
        modalOverlay.classList.remove('show');
        document.body.style.overflow = ''; // permitir scroll otra vez
    };

    if (openModalBtn && modalOverlay) {
        openModalBtn.addEventListener('click', (e) => {
            e.preventDefault();
            modalOverlay.classList.add('show');
            document.body.style.overflow = 'hidden'; // prevenir scroll detrás del modal
        });
    }

    if (closeButton) closeButton.addEventListener('click', closeModal);
    if (cancelButton) cancelButton.addEventListener('click', closeModal);

    // Cerrar si el usuario hace click fuera del modal content
    if (modalOverlay) {
        modalOverlay.addEventListener('click', (e) => {
            if (e.target === modalOverlay) closeModal();
        });
    }

    // Cerrar con tecla Escape
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && modalOverlay && modalOverlay.classList.contains('show')) {
            closeModal();
        }
    });

    // ---------------------------------------------------
    // HANDLE: Crear ticket desde el formulario (POST)
    // ---------------------------------------------------
    const ticketForm = document.querySelector('.ticket-form');

    if (ticketForm) {
        ticketForm.addEventListener('submit', async (e) => {
            e.preventDefault();

            const titleEl = document.getElementById('ticket-title');
            const descEl  = document.getElementById('ticket-description');
            const submitBtn = ticketForm.querySelector('button[type="submit"]');

            const title = titleEl ? titleEl.value.trim() : '';
            const description = descEl ? descEl.value.trim() : '';

            if (!title || !description) {
                alert('Por favor ingresa título y descripción.');
                return;
            }

            // Default status (no hay campo en el modal)
            const status = 'abierto';

            // Intenta obtener user id desde localStorage (si existe) — si no, envía null
            const storedUserId = localStorage.getItem('userId') || localStorage.getItem('userid') || localStorage.getItem('gestorId') || null;
            const userid = storedUserId ? Number(storedUserId) : null;

            const token = localStorage.getItem('authToken') || '';

            // Lock UI
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.textContent = 'Creando...';
            }

            try {
                const response = await fetch('http://127.0.0.1:8000/view/user/createticket', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Authorization': token
                    },
                    body: JSON.stringify({
                        title,
                        description,
                        status,
                        userid
                    })
                });

                if (response.ok) {
                    // Si el backend devuelve JSON, lo procesamos
                    try {
                        const result = await response.json();
                        console.log('Ticket creado:', result);
                    } catch (jsonErr) {
                        console.log('Ticket creado (sin JSON devuelto).');
                    }

                    // Cerrar modal, limpiar formulario y recargar tickets
                    closeModal();
                    ticketForm.reset();
                    queryAllUser();
                } else {
                    // Manejo básico de errores
                    if (response.status === 401) {
                        alert('No autorizado. Verifica tu sesión.');
                    } else {
                        const text = await response.text();
                        console.error('Error al crear ticket:', response.status, text);
                        alert('Hubo un error al crear el ticket. Revisa la consola.');
                    }
                }
            } catch (error) {
                console.error('Error de red al crear ticket:', error);
                alert('Error de conexión. Intenta más tarde.');
            } finally {
                // Unlock UI
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.textContent = '+ Crear Ticket';
                }
            }
        });
    }

});

const queryAllUser = async () => {
    try {
        const token = localStorage.getItem('authToken') || '';
        const response = await fetch('http://127.0.0.1:8000/view/queryallticket', {
            method: 'GET',
            headers: {
                'Authorization': token,
                'Content-Type': 'application/json'
            }
        });

        if (!response.ok) {
            // Manejo básico de errores (puedes mejorar según la API)
            if (response.status === 401) {
                console.warn('No autorizado. Token inválido o ausente.');
            } else if (response.status === 204) {
                console.log('No hay tickets.');
            }
            throw new Error(`HTTP ${response.status}`);
        }

        const data = await response.json();
        const ticketContainer = document.getElementById('tickets22');
        if (!ticketContainer) return;

        // Limpia el contenedor para evitar duplicados (si tienes un template, ajústalo)
        ticketContainer.innerHTML = '';

        if (!data || data.length === 0) {
            ticketContainer.innerHTML = '<p>No hay tickets asignados</p>';
            return;
        }

        const statusMap = {
            'abierto': 'Abierto',
            'en_progreso': 'En Progreso',
            'resuelto': 'Resuelto',
            'cerrado': 'Cerrado'
        };

        data.forEach(item => {
            const card = document.createElement('div');
            card.classList.add('card');

            const cardHeader = document.createElement('div');
            cardHeader.classList.add('card-header');

            const cardTitle = document.createElement('h2');
            cardTitle.classList.add('card-title');
            // Usar los campos que devuelve la API: titulo, descripcion, estado
            cardTitle.textContent = item.titulo || item.title || 'Sin título';

            const statusBadge = document.createElement('div');
            statusBadge.classList.add('status-badge');
            const statusSpan = document.createElement('span');
            statusSpan.textContent = statusMap[item.estado] || item.estado || 'Desconocido';
            statusBadge.appendChild(statusSpan);

            cardHeader.appendChild(cardTitle);
            cardHeader.appendChild(statusBadge);

            const cardDescription = document.createElement('p');
            cardDescription.classList.add('card-description');
            cardDescription.textContent = item.descripcion || item.description || '';

            card.appendChild(cardHeader);
            card.appendChild(cardDescription);

            ticketContainer.appendChild(card);
        });
    } catch (error) {
        console.error('Error en la consulta', error);
    }
};

