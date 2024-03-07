<?php

class formulaire {

    public function addElementInput($nomDiv,$nomLabel,$typeInput,$idInput,$nomInput,$placeInput,$valueInput){
        return "<div class='$nomDiv'>
<label for='$nomLabel'>$nomLabel</label>
<input type='$typeInput' id='$idInput' name='$nomInput' placeholder='$placeInput' value='$valueInput' required>
</div>";
    }

    public function addElementGroupButton($nomDiv, $nomDivButton, $typeInput, $idInput1, $nomInput, $idInput2, $classInput, $classLabel) {
        return "<div class='$nomDiv'>
    <div class='$nomDivButton'>
        <input type='$typeInput' id='$idInput1' name='$nomInput' value='$idInput1' class='$classInput' required>
        <label for='$idInput1' class='$classLabel'>$idInput1</label>
    </div>
    <div class='$nomDivButton'>
        <input type='$typeInput' id='$idInput2' name='$nomInput' value='$idInput2' class='$classInput' required>
        <label for='$idInput2' class='$classLabel'>$idInput2</label>
    </div>
</div>";
    }
}