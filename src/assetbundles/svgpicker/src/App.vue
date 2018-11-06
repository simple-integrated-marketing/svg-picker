<template>
  <div @keydown.enter.prevent.stop="interceptSubmit">
      <div class="section">
        <h2><font-awesome-icon icon="table"/> Summary</h2>
        <hr>
        <div class="wrapper">
            <table class="general-table">
                  <thead>
                      <tr>
                          <th>Total Svgs</th>
                          <th>Total Sets</th>
                      </tr>
                  </thead>
                  <tbody>
                      <tr>
                          <td class="text-right">{{ summary.totalSvg }}</td>
                          <td class="text-right">{{ summary.totalSets }}</td>
                      </tr>
                  </tbody>
            </table>
        </div>
      </div>
      <div class="section">
          <h2><font-awesome-icon icon="cog"/> Manage Svg Set</h2>
          <hr>
          <div class="wrapper" v-if="newSet.isInProgress">
              <div class="wrapper__row" >
                  <swapping-squares-spinner
                          :animation-duration="1000"
                          :size="65"
                          color="#da5a47"
                  />
              </div>
          </div>
          <div class="wrapper" v-else>
              <div class="wrapper__row">
                  Give your set a name
              </div>
              <div class="wrapper__row">
                  <input class="text" type="text" placeholder="Name *" v-model="newSet.name">
              </div>
              <div class="wrapper__row">
                  We will try to get the all the svgs from this remote file (usually it's from Icomoon)
              </div>
              <div class="wrapper__row"><input class="text" type="text" placeholder="Remote Url *" v-model="newSet.importUrl"></div>
              <div class="wrapper__row error" v-if="validation.isSetNameDuplicated">Set Name Exists</div>
              <div class="wrapper__row">
                  <button class="btn submit" :class="{'disabled':validation.isSetNameDuplicated || validation.isSetNameEmpty}" :disabled="validation.isSetNameDuplicated || validation.isSetNameEmpty" type="button" @click="addNewSet">Add new set</button>
              </div>
          </div>
          <div class="wrapper">
              <div v-for="set in settings" class="set-card wrapper__row">
                  <div class="set-card__heading set-card-heading" @click="toggleActiveSet(set)">
                      <div class="set-card-heading__icon"><font-awesome-icon icon="object-group" size="lg"></font-awesome-icon></div>
                      <div class="set-card-heading__content">{{ set.name }}</div>
                      <div class="set-card-heading__icon" @click.stop="removeSet(set)"><font-awesome-icon icon="times" size="lg" style="color:#da5a47"></font-awesome-icon></div>
                  </div>
                  <div class="set-card__content set-card-content" v-if="set.name == activeSetName">
                      <div v-for="(svg,svgIndex) in set.svgs" class="svg-card">
                          <div class="svg-card__name">
                              <span>#</span><input v-model="svg.id">
                          </div>
                          <div class="svg-card__preview" v-html="generateSvgHtml(svg)" @click="sendToClipboard(svg.symbol)">
                          </div>
                          <div class="svg-card__warning warning" v-if="isSvgIdDuplicated(svg)">
                              <div><font-awesome-icon icon="info-circle"/> The id is duplicated.</div>
                          </div>
                          <div class="svg-card__control">
                              <label>Move to </label>
                              <select v-model="moveSvgTo" @change="handleSvgMoveSelect(svg, svgIndex, set.name, $event)">
                                  <option value="">select ...</option>
                                  <option v-for="toSet in settings" v-if="toSet.name != set.name" :value="toSet.name">{{toSet.name}}</option>
                              </select>
                          </div>
                          <div class="svg-card__delete" @click.stop="removeSvgInSet(svgIndex,set)">
                              <font-awesome-icon icon="times" style="color:#da5a47"></font-awesome-icon>
                          </div>
                      </div>
                      <div class="svg-card-add">
                          <div class="wrapper__row"><input class="text fullwidth" type="text" v-model="newSvg.id" placeholder="Id *"></div>
                          <div class="wrapper__row"><input class="text fullwidth" type="text" v-model="newSvg.viewBox" placeholder="ViewBox *"></div>
                          <div class="wrapper__row"><textarea class="text fullwidth" type="text" v-model="newSvg.symbol" placeholder="Symbol *"></textarea></div>
                          <div class="wrapper__row error" v-if="validation.isSvgViewBoxInvalid">ViewBox is invalid (eg. 0 0 128 128)</div>
                          <div class="wrapper__row"><button class="btn submit fullwidth" :class="{'disabled':validation.isSvgInvalid }" :disabled="validation.isSvgInvalid" @click="addNewSvgToSet(set.name)" type="button">Add new svg</button></div>
                      </div>
                  </div>
              </div>
          </div>
      </div>

      <input type="hidden" name="settings[json]" :value="JSON.stringify(settings)">

  </div>
</template>

<script>
  import _ from 'lodash';
  import fontawesome from '@fortawesome/fontawesome'
  import FontAwesomeIcon from '@fortawesome/vue-fontawesome';
  import freeRegular from '@fortawesome/fontawesome-free-regular';
  import freeSolid from '@fortawesome/fontawesome-free-solid';
  import axios from 'axios';
  import { SwappingSquaresSpinner  } from 'epic-spinners';
  let $ = window.jQuery;
  fontawesome.library.add(freeRegular,freeSolid);
  export default {
      name: 'app',
      data () {
          console.log(JSON.parse(document.getElementById("settings-json").value));
          return {
              settings: JSON.parse(document.getElementById("settings-json").value),
              newSet: {
                  name: "",
                  importUrl: "",
                  isInProgress:false,
              },
              newSvg: {
                  id:"",
                  viewBox:"",
                  symbol:"",
              },
              activeSetName:"",
              moveSvgTo:"",
          }
      },
      components: {
          FontAwesomeIcon,
          SwappingSquaresSpinner,
      },
      computed: {
          summary() {
              return {
                  totalSets: this.settings.length,
                  totalSvg: this.allSvgs.length,
              }
          },
          validation() {
              let isSetNameDuplicated = _.filter(this.settings, (o)=> { return o.name == this.newSet.name }).length > 0;
              let isSetNameEmpty = this.newSet.name == "";
              let isSvgViewBoxInvalid = this.newSvg.viewBox!="" && !/\d+\s\d+\s\d+\s\d+/.test(this.newSvg.viewBox);
              let isSvgInvalid = isSvgViewBoxInvalid || this.newSvg.id == "" || this.newSvg.viewBox == "";
              return {
                  isSetNameDuplicated,
                  isSetNameEmpty,
                  isSvgViewBoxInvalid,
                  isSvgInvalid
              }
          },
          allSvgs() {
              let allSvgs = [];
              _.each(this.settings,(o)=>{ allSvgs = allSvgs.concat(o.svgs) });
              return allSvgs;
          }
      },
      methods: {
          addNewSet() {
              // Remote import ?
              if (this.newSet.importUrl!="") {
                  this.newSet.isInProgress = true;
                  let url = this.newSet.importUrl;
                  axios.get(url)
                      .then((res)=>{
                          let svgRaw = res.data;
                          let $svgParsed = $(svgRaw);
                          let svgs = [];
                          $svgParsed.find('symbol').each(function(){
                              svgs.push({
                                  id:$(this).attr('id'),
                                  viewBox: $(this).attr('viewBox'),
                                  symbol: $(this).html(),
                              });
                          })
                          this.settings.push({ name: this.newSet.name, svgs: svgs });
                          this.newSet.name = "";
                          this.newSet.importUrl = "";
                          this.newSet.isInProgress = false;
                      })
                      .catch((o)=>{
                          alert("Remote url invalid");
                          this.newSet.isInProgress = false;
                      });
              } else {
                  this.settings.push({ name: this.newSet.name, svgs: [] });
                  this.newSet.name = "";
              }

          },
          addNewSvgToSet(setName) {
              for (let i = 0; i<this.settings.length; i++) {
                  if (this.settings[i].name == setName) {
                      this.settings[i].svgs.push(this.newSvg);
                  }
              }
              this.newSvg = {
                  id:"",
                  viewBox:"",
                  symbol:"",
              }
          },
          removeSet(set) {
              this.settings = _.filter(this.settings, (o)=> {return o.name != set.name });
          },
          removeSvgInSet(svgIndex,targetSet) {
              let settings = [];
              _.each(this.settings, (set)=>{
                  let newSet = {
                      name:set.name,
                      svgs:set.svgs
                  }
                  if (set.name == targetSet.name) {
                      set.svgs.splice(svgIndex,1)
                      newSet = {
                          name: set.name,
                          svgs: set.svgs
                      }
                  }
                  settings.push(newSet);
              })
              this.settings = settings;
          },
          handleSvgMoveSelect(svg, svgIndex, fromSetName, e){
              let toSetName = e.target.value;
              if (toSetName) {
                  this.moveSvgToSet(svg,svgIndex,fromSetName,toSetName);
              }
          },
          moveSvgToSet(svg,svgIndex,fromSetName,toSetName) {
              let settings = [];
              _.each(this.settings, (set)=>{
                  let newSet = {
                      name:set.name,
                      svgs:set.svgs
                  }
                  if (set.name == fromSetName) {
                      set.svgs.splice(svgIndex,1)
                      newSet = {
                          name: set.name,
                          svgs: set.svgs
                      }
                  }
                  if (set.name == toSetName) {
                      newSet = {
                          name: set.name,
                          svgs: set.svgs.concat(svg)
                      }
                  }
                  settings.push(newSet);
              })
              this.settings = settings;
              this.moveSvgTo = "";
          },
          generateSvgHtml(svg) {
              let html = `<svg xmlns="http://www.w3.org/2000/svg" viewBox="${svg.viewBox}">${svg.symbol}</svg>`;
              return html;
          },
          toggleActiveSet(set) {
              if (this.activeSetName==set.name) {
                  this.activeSetName="";
              } else {
                  this.activeSetName=set.name;
              }
          },
          isSvgIdDuplicated(svg) {
              let id = svg.id;
              return _.filter(this.allSvgs,(o)=>(id==o.id)).length>1;
          },
          sendToClipboard(content) {
              this.$copyText(content);
              Craft.cp.displayNotice('Svg symbol content has been copied to your clipboard.')
          },
          interceptSubmit() {
          }
      }
  }
</script>

<style lang="scss" scoped>
    .text-right {
        text-align:right;
    }
    .text-left {
        text-align:left;
    }
    .general-table {
        border:1px solid #eeeeee;
        thead {
            tr {
                th {
                    border-bottom:1px solid #da5a47;
                }
            }
        }
        td,th {
            padding:7.5px 15px;
        }
    }
    .section {
        margin-bottom:40px;
    }
    .wrapper {
        margin: 20px 0;
        &__row {
            margin-bottom:10px;
        }
    }
    .set-card {
        border:1px solid #e3e3e3;
        &__heading {

        }
        &__content {
            border-top:1px solid #e3e3e3;
        }
    }
    .set-card-heading {
        display:flex;
        align-items:center;
        cursor:pointer;
        font-size:20px;
        &__icon {
            flex:0 0 auto;
            width:auto;
            padding:10px;
            background:#e3e3e3;
        }
        &__content {
            padding:10px;
            flex:1 1 auto;
        }
    }
    .set-card-content {
        display:flex;
        flex-flow:row wrap;

        .svg-card {
            flex:0 0 auto;
            width:200px;
            margin:20px;
            border:1px solid #e3e3e3;
            border-radius:5px;
            box-shadow:0 2px 2px 0 #e3e3e3;
            position:relative;
            display:flex;
            flex-flow: column nowrap;
            justify-content: space-between;

            &__name {
                background: #e3e3e3;
                padding:10px 30px 10px 10px;
                border-bottom:1px solid #e3e3e3;
                display:flex;
                flex-flow:row nowrap;
                input {
                    background-color:transparent;
                    display:inline-block;
                    border:0;
                }
            }
            &__preview{
                position:relative;
                &::after {
                    display: none;
                    cursor: pointer;
                    align-items: center;
                    justify-content: center;
                    color: white;
                    text-align: center;
                    content: '🖱 Click to copy 📋';
                    background-color: rgba(0, 0, 0, 0.3);
                    top: 0;
                    bottom: 0;
                    right: 0;
                    left: 0;
                    position: absolute;
                }
                &:hover {
                    &::after {
                        display:flex;
                    }
                }
            }
            &__preview,&__warning,&__control {
                padding:10px;
            }
            &__control {
                margin-top:auto;
            }

            &__delete {
                position:absolute;
                padding:10px;
                top:0;
                right:0;
                cursor:pointer;
            }
        }
        .svg-card-add {
            padding:20px;
            border-top:1px solid #e3e3e3;
            flex:1 1 auto;
            width:100%;
        }
    }


</style>
