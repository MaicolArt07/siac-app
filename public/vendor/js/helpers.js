
function notificacion()

{
    Livewire.on('notificar', function (data) 
    {
        console.log(data);
        if(data.message)
        {
            Swal.fire({
                position: "center",
                icon: "success",
                title: "Guardado Correctamente!",
                showConfirmButton: false,
                timer: 1500,
            });
        }else{
            Swal.fire({
                position: "center",
                icon: "error",
                title: "Algo sucedio!",
                showConfirmButton: false,
                timer: 1500,
            });
        }
    });
}

function confirmacion(component, metodo)
{
    Livewire.on('confirmDelete', function (id) 
    {
        Swal.fire({
            title: "ADVERTENCIA?",
            text: "Deseas continuar con la acción?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Aceptar",
            cancelButtonText: "Cancelar"
            
        }).then((result) => {
            // console.log(result.value);
            if (result.value) 
            {
                Livewire.dispatch(component, {id: id});
                Swal.fire({
                    position: "center",
                    icon: "success",
                    title: "Accion ejecutada correctamente!",
                    showConfirmButton: false,
                    timer: 1500,
                });
            }   
        });
    });
}
const buttonCerraModal = document.querySelectorAll(".buttonCerraModal");
    buttonCerraModal.forEach(button => {
        button.addEventListener('click', () => {
            if(button.classList.contains('buttonCerraModal'))
            {
                // mensaje.parentNode.innerHTML = '';
                console.log("Cerro modal");
            }
        });
    });
    
function removerMensajeValidacion()
{
    
}
