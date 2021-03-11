package main

import (
	"fmt"
	"math/rand"
	"sort"
	"time"
)

func main() {
	var playerInput int
	var diceInput int
	fmt.Print("Enter Player Count: ")
	fmt.Scan(&playerInput)
	fmt.Println("Player Count: ", playerInput)
	fmt.Print("Enter Dice Count: ")
	fmt.Scan(&diceInput)
	fmt.Println("Dice Count: ", diceInput)

	if playerInput < 1 || diceInput < 1 {
		panic("Pemain atau Dadu tidak mencukupi untuk melakukan permainan ini")
	}
	playerDices := make([]int, playerInput)
	for i := range playerDices {
		playerDices[i] = diceInput
	}
	playerScores := make([]int, playerInput)
	for i := range playerScores {
		playerScores[i] = 0
	}

	isActive := func(i int) bool { return i > 0 }
	activePlayer := len(filter(playerDices, isActive))

	if activePlayer == 1 {
		panic("Pemain hanya 1, jadi pemain tersebut dianggap menang tanpa bermain, selamat ya")
	}
	turn := 0
	tempScores := make([]int, len(playerScores))
	for activePlayer > 1 {

		var diceResults = make([][]int, playerInput)
		var evaluateResults = make([][]int, playerInput)

		copy(tempScores, playerScores)

		for player, dice := range playerDices {
			for i := 0; i < dice; i++ {
				rand.Seed(time.Now().UTC().UnixNano())
				diceResult := randInt(1, 7)
				diceResults[player] = append(diceResults[player], diceResult)
				if diceResult == 6 {
					playerScores[player]++
					playerDices[player]--
				} else if diceResult == 1 {
					playerDices[player] -= 1
					if len(playerDices) > player+1 {
						playerDices[player+1]++
						evaluateResults[player+1] = append(evaluateResults[player+1], diceResult)
					} else {
						playerDices[0]++
						evaluateResults[0] = append(evaluateResults[0], diceResult)
					}
				} else {
					evaluateResults[player] = append(evaluateResults[player], diceResult)
				}
			}
		}
		turn++

		fmt.Println("Giliran ", turn, " lempar dadu")
		for player := range playerDices {
			fmt.Println("Pemain #", player+1, "(", tempScores[player], ")", diceResults[player])
		}
		fmt.Println("Setelah Evaluasi")
		for player := range playerDices {
			fmt.Println("Pemain #", player+1, "(", playerScores[player], ")", evaluateResults[player])
		}
		fmt.Println("===========")

		activePlayer = len(filter(playerDices, isActive))
		if activePlayer == 1 {

			lastManStanding := filterIndex(playerDices, isActive)
			fmt.Println("Game berakhir karena hanya pemain #", lastManStanding, "yang memiliki dadu")
			sort.Ints(playerScores)
			winner := playerScores[len(playerScores)-1]
			fmt.Println("Game dimenangkan oleh pemain #", winner, "karena memiliki poin lebih banyak dari pemain lainnya.")
		}
	}
}

func filter(ss []int, test func(int) bool) (ret []int) {
	for _, s := range ss {
		if test(s) {
			ret = append(ret, s)
		}
	}
	return
}
func filterIndex(ss []int, test func(int) bool) (ret int) {
	for i, s := range ss {
		if test(s) {
			ret = i + 1
		}
	}
	return
}

func randInt(min int, max int) int {
	return min + rand.Intn(max-min)
}
