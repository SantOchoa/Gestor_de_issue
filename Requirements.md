# Requerimientos 
- [x] El sistema deberá dividirse en mínimo 2 microservicios independientes.
- [x] Los users pueden iniciar sesion.
- [ ] La aplicación debe tener una pantalla de inicio de sesión.
## Auth Tokens
- [x] El sistema debe generar un token aleatorio al iniciar sesión (Uno para Admi y otro para gestor).
- [x] El token generado debe almacenarse en la base de datos del usuario. (En auth_tokens)
- [ ] El frontend debe almacenar el token del usuario iniciado en sessionStorage.
- [x] El sistema debe permitir cerrar sesión, eliminando el token almacenado. (Eliminar en sessionStorage y BataBase)
- [ ] Para consumir cualquier endpoint (excepto inicio) el frontend debe enviar el token.
- [x] El backend debe validar que el token exista y sea válido.
- [x] Si el token no existe o no es válido, el sistema debe impedir el acceso a los endpoints.
- [x] Si el token no es válido desde el frontend, el usuario debe ser enviado a la página de inicio de sesión.
## Users Type
- [x] El sistema debe manejar dos roles Admi y gestor.
### Manager
- [x] El gestor solo puede crea tickets y realiza seguimiento (Post y Get).
### Admin
- [ ] El admid gestiona tickets y gestiona usuarios (Get, Put y Delete).
- [ ] El administrador debe poder listar usuarios. (Get)
- [ ] El administrador debe poder actualizar la información de los usuarios. (Put)
- [ ] El administrador debe poder cambiar el rol de un usuario. (Put)
- [ ] El administrador debe poder eliminar usuarios. (Delete)
## Tickets
- [ ] Cada ticket debe guardar historial de comentarios. (Una vista especifica de los comentarios hechos) 
- [ ] El sistema debe permitir buscar y filtrar tickets por estado, creador o asignación. 
### Manager
- [ ] Solo los usuarios con rol gestor deben poder crear tickets. (No se le va asignar a un admi especifico, cada que un admid modifique el tiket se le asignara) (Post)
- [ ] Los gestores deben poder ver los tickets que han creado. (Get)
- [ ] Los gestores deben poder agregar comentarios a sus tickets. (En ticket_actividad) (Post)
### Admin
- [ ] Los administradores deben poder ver todos los tickets. (Get)
- [ ] Los administradores deben poder actualizar el estado de un ticket (abierto, en progreso, cerrado). (Put)
- [ ] Los administradores deben poder asignar tickets a otros administradores.(Es un asignaiento temporal no quita privilegios a otros admin)(Put)
- [ ] Los administradores deben poder agregar comentarios a los tickets. (En ticket_actividad) (Post)
- [ ] El sistema debe permitir buscar y filtrar tickets por estado, creador o asignación. (Get)