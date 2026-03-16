<?php

namespace App\Services;

class ThermalTicketService
{
    /**
     * Comandos ESC/POS estándar (formato RAW)
     */
    private const ESC = "\x1b";

    private const GS = "\x1d";

    private const LF = "\x0a";

    private const INITIALIZE = self::ESC.'@';

    private const ALIGN_CENTER = self::ESC.'a1';

    private const ALIGN_LEFT = self::ESC.'a0';

    private const BOLD_ON = self::ESC.'E1';

    private const BOLD_OFF = self::ESC.'E0';

    private const BARCODE_WIDTH = self::GS.'w3'; // Ancho barra

    private const BARCODE_HEIGHT = self::GS.'h100'; // Altura 100

    private const BARCODE_TEXT_BELOW = self::GS.'H2'; // Texto abajo

    /**
     * Genera el formato RAW para un ticket de ingreso de parqueo.
     */
    public function generateParkingEntryTicket(\App\Models\ParkingSession $session): string
    {
        $ticket = self::INITIALIZE;
        $ticket .= self::ALIGN_CENTER.self::BOLD_ON.'CENTRO DE SERVICIOS VEHICULARES'.self::LF;
        $ticket .= 'Calle Falsa 123 - Ciudad'.self::LF;
        $ticket .= 'NIT: 123.456.789-0'.self::LF.self::BOLD_OFF;
        $ticket .= '--------------------------------'.self::LF;
        $ticket .= 'TICKET DE INGRESO # '.str_pad($session->id, 6, '0', STR_PAD_LEFT).self::LF;
        $ticket .= self::LF;

        $ticket .= self::ALIGN_LEFT;
        $ticket .= 'PLACA: '.$session->vehicle->plate.self::LF;
        $ticket .= 'TIPO:  '.$session->vehicle->type.self::LF;
        $ticket .= 'FECHA: '.$session->entry_time->format('d/m/Y H:i').self::LF;
        $ticket .= '--------------------------------'.self::LF;

        // Generación de Código de Barras (Code 128)
        $barcodeData = (string) $session->id;
        $ticket .= self::ALIGN_CENTER;
        $ticket .= self::BARCODE_WIDTH.self::BARCODE_HEIGHT.self::BARCODE_TEXT_BELOW;
        $ticket .= $this->getBarcodeRaw($barcodeData);
        $ticket .= self::LF.self::LF;

        $ticket .= 'Escanee para registrar salida'.self::LF;
        $ticket .= self::LF.self::LF.self::LF; // Espacio para corte
        $ticket .= self::GS.'V1'; // Corte de papel

        return $ticket;
    }

    /**
     * Formatea el comando de código de barras según el protocolo ESC/POS (Code 128).
     */
    private function getBarcodeRaw(string $data): string
    {
        // GS k m n d1...dn (m=73 para Code 128)
        return self::GS.'k'.chr(73).chr(strlen($data)).$data;
    }
}
