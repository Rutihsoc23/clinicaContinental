describe('SIMICRA - Pruebas de Control de Acceso y Roles', () => {

    // Escenario 1: Usuario Anónimo
    it('RF13 - Invitado: Debe redirigir al Login si intenta entrar a Doctores', () => {
        cy.visit('http://127.0.0.1:8000/doctores', { failOnStatusCode: false });
        cy.url().should('include', '/login');
    });

    describe('SIMICRA - Validación de Ingreso por Rol', () => {

    // Escenario 2: Recepcionista - Solo validamos que pueda entrar al sistema
    it('RF14 - Recepcionista: Debe iniciar sesión y acceder al panel principal', () => {
        // 1. Ir al login
        cy.visit('http://127.0.0.1:8000/login');

        // 2. Ingresar credenciales de recepcionista
        cy.get('input[name="email"]').type('recepcion@clinica.com');
        cy.get('input[name="password"]').type('recepcion123');
        
        // 3. Enviar formulario (usando el método submit para evitar errores de clic)
        cy.get('form').submit();

        // 4. VERIFICACIÓN: Comprobar que ya no está en login y ve la bienvenida
        // Esto confirma que el ingreso fue exitoso
        cy.url().should('not.include', '/login');
        cy.get('body').should('be.visible');
        
        // Verificamos que vea el nombre del sistema o un elemento común del dashboard
        cy.contains(/CLÍNICA CONTINENTAL|Bienvenido|Dashboard/i).should('be.visible');
        
        // Verificación de seguridad: No debe ver el enlace de Doctores en el menú
        cy.get('body').should('not.contain', 'Doctores');
    });

});

    // Escenario 3: Administrador
    it('RF15 - Administrador: Debe acceder libremente a la gestión de doctores', () => {
        // 1. Login
        cy.visit('http://127.0.0.1:8000/login');
        cy.get('input[name="email"]').type('admin@clinica.com');
        cy.get('input[name="password"]').type('admin123');
        cy.get('form').submit();

        // 2. Navegar
        cy.visit('http://127.0.0.1:8000/doctores');

        // CAMBIO AQUÍ: Quitamos la restricción de 'h5' para que encuentre el texto aunque tenga iconos
        cy.url().should('include', '/doctores');
        cy.contains(/Doctores|Médicos|Gestión/i).should('be.visible');
        cy.get('table').should('exist');
    });
});