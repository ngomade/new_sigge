import React, { useEffect, useRef } from 'react';
import * as pdfjsLib from 'pdfjs-dist';
import 'pdfjs-dist/build/pdf.worker.mjs';
const PDFPreview = ({ pdfUrl }) => {
  const canvasRef = useRef(null);

  useEffect(() => {
    const loadPdf = async () => {
      const loadingTask = pdfjsLib.getDocument(pdfUrl);
      const pdf = await loadingTask.promise;
      const page = await pdf.getPage(1);
      const viewport = page.getViewport({ scale: 1 });
      const canvas = canvasRef.current;
      const context = canvas.getContext('2d');

      canvas.height = viewport.height/5;
      canvas.width =  viewport.width/10;

      const renderContext = {
        canvasContext: context,
        viewport: viewport,
      };
      page.render(renderContext);
    };

    loadPdf();
    return function(){
      loadPdf()
    }
  }, [pdfUrl]);

  return <canvas ref={canvasRef} className="border border-gray-300 rounded shadow-md" />;
};

export default PDFPreview;
