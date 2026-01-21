
@extends('Layouts.layout')

@section('titel')
   Keuzedeel
@endsection
@section('content')  
           <div class=" h:screen bg-gradient-to-t from-[#330469] to-[#911be0]  h-scree md:flex-row flex flex-col  md:justify-center-safe items-center p-2 ">
               <div class=" max-w-130 max-h-100 p-2 m-2">
                  <h1 class=" p-6  text-3xl text-amber-50 font-bold" >Hét platform  voor keuzedelen</h1>
                  <p class="p-6 text-amber-50 font-bold" >Lorem, ipsum dolor sit amet consectetur adipisicing elit. Reiciendis, harum? Distinctio, commodi. Pariatur, excepturi iste laudantium aspernatur suscipit rerum enim?</p>
               </div>
               <div class="imag">
                    <img class=" w-100" src="/imags/keuzedeel.svg" alt="...">
               </div>
               
           </div>
           <div class=" h:screen shadow-3xl bg-white flex flex-col items-center ">
                 <h1 class=" p-6 text-3xl font-bold text-indigo-950 ">Ons lesaanbod <br>----------------- </h1>

                 <div class="Carts p-6 grid  grid-cols-4    gap-5" >
                    
                 
                   <div   class="  rounded-xl relative w-72 h-96  overflow-hidden ">  
                          <a href="#">
                            {{-- imag --}}
                            <img  class=" shadow-[inset_10px_-30px_30px_rgba(0,0,0,1)] w-full h-full rounded-xl" src="/imags/allyoucanlearn-keuzedeel-ondernemend-handelen.jpg" alt="">
                              
                            {{-- status --}}
                            <div class="absolute top-0 bg-black/20  shadow-[inset_10px_-30px_30px_rgba(0,0,0,1)] w-full h-full ">
                            <div class="absolute top-1 left-4 flex  " >   
                                <h2 class=" bottom-1 p-2 bg-[#911be0] rounded-l text-sm  text-white">Active</h2>
                            </div> 
                            </div>
                             {{-- title --}}
                            <div class="  p-2 absolute bottom-1">
                                <p   class="    text-xl  text-teal-50  font-bold"> Gevalideerd examen Keuzedeel Ondernemend handelen (K1481)</p>
                            </div>
                            </a> 
                    </div>
                
                    
                 </div>
                 




           </div>
            
     
@endsection