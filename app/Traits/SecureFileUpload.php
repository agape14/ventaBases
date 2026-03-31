<?php

namespace App\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

/**
 * Trait para manejo seguro de subida de archivos
 * 
 * PARCHE DE SEGURIDAD - Enero 2026
 * Previene ataques de Web Shell mediante validación estricta
 */
trait SecureFileUpload
{
    /**
     * Extensiones permitidas para imágenes
     */
    protected $allowedImageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    
    /**
     * MIME types permitidos para imágenes
     */
    protected $allowedImageMimes = [
        'image/jpeg',
        'image/png', 
        'image/gif',
        'image/webp'
    ];

    /**
     * Extensiones permitidas para documentos
     */
    protected $allowedDocExtensions = ['pdf', 'doc', 'docx'];
    
    /**
     * MIME types permitidos para documentos
     */
    protected $allowedDocMimes = [
        'application/pdf',
        'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
    ];

    /**
     * Valida y procesa una imagen de forma segura
     * 
     * @param UploadedFile $file
     * @return array ['success' => bool, 'filename' => string|null, 'error' => string|null]
     */
    protected function processSecureImage(UploadedFile $file): array
    {
        // 1. Validar extensión
        $extension = strtolower($file->getClientOriginalExtension());
        if (!in_array($extension, $this->allowedImageExtensions)) {
            return [
                'success' => false,
                'filename' => null,
                'error' => 'Extensión de archivo no permitida. Solo se permiten: ' . implode(', ', $this->allowedImageExtensions)
            ];
        }

        // 2. Validar MIME type real del archivo (no el que dice el cliente)
        $mimeType = $file->getMimeType();
        if (!in_array($mimeType, $this->allowedImageMimes)) {
            return [
                'success' => false,
                'filename' => null,
                'error' => 'Tipo de archivo no permitido. El archivo no es una imagen válida.'
            ];
        }

        // 3. Verificar que realmente es una imagen usando getimagesize()
        $imageInfo = @getimagesize($file->getPathname());
        if ($imageInfo === false) {
            return [
                'success' => false,
                'filename' => null,
                'error' => 'El archivo no es una imagen válida.'
            ];
        }

        // 4. Verificar tamaño máximo (2MB por defecto)
        if ($file->getSize() > 2097152) { // 2MB en bytes
            return [
                'success' => false,
                'filename' => null,
                'error' => 'El archivo excede el tamaño máximo permitido (2MB).'
            ];
        }

        // 5. Generar nombre seguro (hash aleatorio + extensión validada)
        // NUNCA usar getClientOriginalName()
        $secureFilename = Str::random(40) . '.' . $extension;

        return [
            'success' => true,
            'filename' => $secureFilename,
            'error' => null
        ];
    }

    /**
     * Valida y procesa un documento PDF de forma segura
     * 
     * @param UploadedFile $file
     * @return array ['success' => bool, 'filename' => string|null, 'error' => string|null]
     */
    protected function processSecurePDF(UploadedFile $file): array
    {
        // 1. Validar extensión
        $extension = strtolower($file->getClientOriginalExtension());
        if ($extension !== 'pdf') {
            return [
                'success' => false,
                'filename' => null,
                'error' => 'Solo se permiten archivos PDF.'
            ];
        }

        // 2. Validar MIME type
        $mimeType = $file->getMimeType();
        if ($mimeType !== 'application/pdf') {
            return [
                'success' => false,
                'filename' => null,
                'error' => 'El archivo no es un PDF válido.'
            ];
        }

        // 3. Verificar magic bytes del PDF
        $handle = fopen($file->getPathname(), 'rb');
        $header = fread($handle, 5);
        fclose($handle);
        
        if ($header !== '%PDF-') {
            return [
                'success' => false,
                'filename' => null,
                'error' => 'El archivo no es un PDF válido.'
            ];
        }

        // 4. Verificar tamaño máximo (10MB)
        if ($file->getSize() > 10485760) { // 10MB
            return [
                'success' => false,
                'filename' => null,
                'error' => 'El archivo excede el tamaño máximo permitido (10MB).'
            ];
        }

        // 5. Generar nombre seguro
        $secureFilename = Str::random(40) . '.pdf';

        return [
            'success' => true,
            'filename' => $secureFilename,
            'error' => null
        ];
    }

    /**
     * Mueve un archivo de forma segura al destino
     * 
     * @param UploadedFile $file
     * @param string $secureFilename
     * @param string $destination Ruta relativa desde public_path()
     * @return bool
     */
    protected function moveSecureFile(UploadedFile $file, string $secureFilename, string $destination): bool
    {
        $fullPath = public_path($destination);
        
        // Crear directorio si no existe
        if (!file_exists($fullPath)) {
            mkdir($fullPath, 0755, true);
        }

        try {
            $file->move($fullPath, $secureFilename);
            return true;
        } catch (\Exception $e) {
            \Log::error('Error moviendo archivo: ' . $e->getMessage());
            return false;
        }
    }
}
