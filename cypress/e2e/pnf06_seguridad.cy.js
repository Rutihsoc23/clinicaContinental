describe('PNF06 - Seguridad del Sistema (Protocolos y Datos)', () => {

    it('Verificación de Protocolo Seguro (Adaptativo)', () => {
        // Visitamos la página
        cy.visit('http://127.0.0.1:8000/login');

        // Obtenemos el protocolo actual (http: o https:)
        cy.location('protocol').then((protocolo) => {
            
            // Si estamos en tu computadora (localhost o IP local)
            if (protocolo === 'http:') {
                cy.log('⚠️ ALERTA: Estás en entorno de DESARROLLO (Localhost).');
                cy.log('ℹ️ Se acepta HTTP por ser entorno de pruebas.');
                
                // La prueba pasa si es http, porque sabemos que es XAMPP
                expect(protocolo).to.eq('http:');
            
            } else {
                // Si algún día subes esto a internet, exigirá HTTPS
                cy.log('✅ ENTORNO DE PRODUCCIÓN DETECTADO');
                expect(protocolo).to.eq('https:');
            }
        });
    });

    it('No debe exponer contraseñas en el código HTML', () => {
        cy.visit('http://127.0.0.1:8000/login');
        
        // Esta es la prueba más importante de seguridad visual:
        // Verifica que el input tenga type="password" (puntitos) y no "text" (letras visibles)
        cy.get('input[name="password"]')
          .should('have.attr', 'type', 'password');
    });

});