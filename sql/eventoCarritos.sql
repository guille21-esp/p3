DELIMETER //
CREATE EVENT IF NOT EXISTS limpiar_carritos_temporales
ON SCHEDULE EVERY 1 DAY
DO
BEGIN
    DELETE FROM Carrito_Ventas
    WHERE Fecha_Expiracion < NOW()
    AND ID_Cliente IS NULL;
END //
DELIMETER;