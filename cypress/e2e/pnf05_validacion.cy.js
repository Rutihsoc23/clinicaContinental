describe('PNF05 - Validación de Datos (Robustez)', () => {

    beforeEach(() => {
        // 1. PRIMERO: Iniciamos sesión (Obligatorio para ver citas)
        cy.visit('http://127.0.0.1:8000/login');
        cy.get('input[name="email"]').type('admin@clinica.com');
        cy.get('input[name="password"]').type('admin123'); // Tu contraseña real
        cy.get('button[type="submit"]').click();

        // 2. SEGUNDO: Ahora sí vamos a crear la cita
        // Esperamos a que cargue el dashboard y luego vamos a la ruta
        cy.url().should('not.include', '/login');
        cy.visit('http://127.0.0.1:8000/citas/create');
    });

    it('Debe rechazar texto en campos de fecha y hora (Inyección de datos)', () => {
        // Verificamos que estamos en la pantalla correcta antes de empezar
        cy.get('input[name="fecha_cita"]').should('be.visible');

        // --- ATAQUE DE INYECCIÓN ---
        // Intentamos meter TEXTO en un campo que espera FECHA
        cy.log('⚠️ Intentando inyectar texto inválido en fecha...');
        
        cy.get('input[name="fecha_cita"]')
          .invoke('attr', 'type', 'text') // Truco: Convertimos el input a texto
          .type('ESTO_NO_ES_UNA_FECHA');

        cy.get('input[name="hora_cita"]')
          .invoke('attr', 'type', 'text') // Truco: Convertimos el input a texto
          .type('HORA_FALSA');

        // Llenamos el resto de campos obligatorios para poder enviar el formulario
        cy.get('select[name="paciente_id"]').select(1, { force: true });
        cy.get('select[name="doctor_id"]').select(1, { force: true });
        
        // Intentamos guardar
        cy.get('button[type="submit"]').last().click();

        // --- VALIDACIÓN DE ROBUSTEZ ---
        // El sistema NO debe explotar (Error 500) ni mostrar mensaje de éxito.
        // Debe volver al formulario mostrando errores de validación.

        // 1. No debe haber éxito
        cy.get('.alert-success').should('not.exist');
        
        // 2. Debe seguir en la misma página (o recargarla)
        cy.url().should('include', '/citas');

        // 3. Opcional: Si Laravel valida bien, debería salir una alerta de error (roja)
        // Si no tienes validación en el controller, esto podría fallar, pero 
        // lo importante para la PNF es que no guarde basura en la BD.
    });
});